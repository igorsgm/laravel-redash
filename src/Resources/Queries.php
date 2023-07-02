<?php

namespace Igorsgm\Redash\Resources;

use Exception;
use Igorsgm\Redash\Enums\JobStatus;
use Igorsgm\Redash\Facades\Redash;
use Igorsgm\Redash\Resources\Concerns\HasParams;
use Illuminate\Support\Facades\Http;

class Queries
{
    use HasParams;

    /**
     * Returns a paginated array of query objects.
     * Includes the most recent query_result_id for non-parameterized queries.
     *
     * @return array
     */
    public function all()
    {
        return Http::redash($this->params)->get('/api/queries')->json();
    }

    /**
     * Returns an individual query object.
     *
     * @return array
     */
    public function get(int|string $id)
    {
        return Http::redash($this->params)->get("/api/queries/{$id}")->json();
    }

    /**
     * Create a new query object.
     *
     * @return array
     */
    public function create(array $data)
    {
        return Http::redash($this->params)->post('/api/queries', $data)->json();
    }

    /**
     * Edit an existing query object.
     *
     * @return array
     */
    public function update(int|string $id, array $data)
    {
        return Http::redash($this->params)->post("/api/queries/{$id}", $data)->json();
    }

    /**
     * Archive this query.
     *
     * @return array
     */
    public function delete(int|string $id)
    {
        return Http::redash($this->params)->delete("/api/queries/{$id}")->json();
    }

    /**
     * Get a cached result for this query ID.
     * Only works for non parameterized queries.
     * If you attempt to GET results for a parameterized query you’ll receive the error: "no cached result found for this query".
     *
     * @return array
     */
    public function getCachedResult(int|string $id)
    {
        return Http::redash($this->params)->get("/api/queries/{$id}/results")->json();
    }

    /**
     * Initiates a new query execution or returns a cached result.
     * The API prefers to return a cached result. If a cached result is not available, a new execution job begins and
     * the job object is returned. To bypass a stale cache, include a max_age key which is an integer number of seconds.
     * If the cached result is older than max_age, the cache is ignored and a new execution begins.
     * If you set max_age to 0, this guarantees a new execution.
     *
     * If passing parameters, they must be included in the $data:
     * Here's an example $data array including different parameter types:
     *
     * Example:
     *
     * $data = [
     *     "parameters" => array(
     *         "number_param" => 100,
     *         "date_param" => "2020-01-01",
     *         "date_range_param" => array(
     *             "start" => "2020-01-01",
     *             "end" => "2020-12-31"
     *         )
     *     ),
     *     "max_age" => 1800
     * ];
     *
     * @return array
     */
    public function executeOrGetResult(int|string $id, array $data)
    {
        return Http::redash($this->params)->post("/api/queries/{$id}/results", $data)->json();
    }

    /**
     * Execute a Query and return its result once ready (custom method).
     *
     * @param  int|string  $id The ID of the query to execute.
     * @param  array  $parameters The parameters to include in the query execution.
     * @param  int  $maxAge The maximum age (in seconds) of a cached result that the method should return.
     *        If a cached result is older than this, a new query execution will begin.
     *        Set to `0` to always start a new execution.
     * @param  int  $retryAttempts The number of times to retry the query execution if it is still in progress.
     * @return array The result of the query execution, as an array.
     *
     * @throws Exception If the query execution fails, is cancelled, or is still in progress after 20 retries.
     */
    public function getResult(int|string $id, array $parameters = [], int $maxAge = 1800, int $retryAttempts = 20)
    {
        // Start a new query execution
        $job = $this->executeOrGetResult($id, [
            'parameters' => $parameters,
            'max_age' => $maxAge,
        ]);

        return retry($retryAttempts, function () use ($job) {
            $jobId = data_get($job, 'job.id');
            $jobStatus = Redash::jobs()->get($jobId);

            $status = data_get($jobStatus, 'job.status');
            if ($status == JobStatus::SUCCESS->value) {
                // Job has finished successfully, fetch the result
                return Redash::queryResults()->get(data_get($jobStatus, 'job.query_result_id'));
            } elseif (in_array($status, [JobStatus::FAILURE->value, JobStatus::CANCELLED->value])) {
                // Job has failed or been cancelled, throw an exception
                throw new Exception('Query execution failed or was cancelled');
            }

            // If the job is still pending or started, throw an exception to retry
            throw new Exception('Job still in progress');
        }, 1000);
    }
}
