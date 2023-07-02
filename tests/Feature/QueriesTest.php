<?php

use Igorsgm\Redash\Enums\JobStatus;
use Igorsgm\Redash\Facades\Redash;
use Illuminate\Support\Facades\Http;

it('gets all queries', function () {
    Http::fake([
        '*/queries' => Http::response(['results' => []], 200),
    ]);

    $response = Redash::queries()->all();

    expect($response)->toBeArray()->and($response['results'])->toBeArray();
});

it('gets a query by id', function () {
    $id = 1;

    Http::fake([
        "*/queries/{$id}" => Http::response(['id' => $id], 200),
    ]);

    $response = Redash::queries()->get($id);

    expect($response)->toBeArray()->and($response['id'])->toBe($id);
});

it('creates a new query', function () {
    $data = ['query' => 'SELECT * FROM users'];
    $id = 1;

    Http::fake([
        '*/queries' => Http::response(['id' => $id], 200),
    ]);

    $response = Redash::queries()->create($data);

    expect($response)->toBeArray()->and($response['id'])->toBe($id);
});

it('updates a query', function () {
    $id = 1;
    $data = ['query' => 'SELECT * FROM users WHERE id = 1'];

    Http::fake([
        "*/queries/{$id}" => Http::response(['id' => $id], 200),
    ]);

    $response = Redash::queries()->update($id, $data);

    expect($response)->toBeArray()->and($response['id'])->toBe($id);
});

it('deletes a query', function () {
    $id = 1;

    Http::fake([
        "*/queries/{$id}" => Http::response(null, 200),
    ]);

    $response = Redash::queries()->delete($id);

    expect($response)->toBeNull();
});

it('gets a cached query result', function () {
    $id = 1;

    Http::fake([
        "*/queries/{$id}/results" => Http::response(['query_result' => []], 200),
    ]);

    $response = Redash::queries()->getCachedResult($id);

    expect($response)->toBeArray()->and($response['query_result'])->toBeArray();
});

it('executes or gets a query result', function () {
    $id = 1;

    Http::fake([
        "*/queries/{$id}/results" => Http::response(['job' => []], 200),
    ]);

    $response = Redash::queries()->executeOrGetResult($id, ['max_age' => 0]);

    expect($response)->toBeArray()->and($response['job'])->toBeArray();
});

it('gets a query result', function () {
    $id = 1;

    Http::fake([
        "*/queries/{$id}/results" => Http::response([
            'job' => [
                'id' => $id,
                'status' => JobStatus::SUCCESS->value,
                'query_result_id' => $id,
            ],
        ], 200),
        '*/jobs/1' => Http::response(['job' => ['status' => 3, 'query_result_id' => $id]], 200),
        '*/query_results/1' => Http::response(['query_result' => 'result'], 200),
    ]);

    $response = Redash::queries()->getResult($id);

    expect($response)->toBeArray()->and($response['query_result'])->toBe('result');
});

it('throws an exception if query execution fails or is canceled', function () {
    $id = 1;
    $jobId = 'job-1';

    Http::fake([
        "*/queries/{$id}/results" => Http::response([
            'job' => [
                'id' => $jobId,
                'status' => JobStatus::FAILURE->value,
                'query_result_id' => $id,
            ],
        ], 200),
        "*/queries/{$id}" => Http::response(['job' => ['id' => $jobId]], 200),
        "*/jobs/{$jobId}" => Http::response(['job' => ['status' => JobStatus::FAILURE->value]], 200),
    ]);

    Redash::queries()->getResult($id, [], 0, 1);

})->throws(Exception::class, 'Query execution failed or was cancelled');

it('throws an exception if Job is still in progress', function () {
    $id = 1;
    $jobId = 'job-1';

    Http::fake([
        "*/queries/{$id}/results" => Http::response([
            'job' => [
                'id' => $jobId,
                'status' => JobStatus::PENDING->value,
                'query_result_id' => $id,
            ],
        ], 200),
        "*/queries/{$id}" => Http::response(['job' => ['id' => $jobId]], 200),
        "*/jobs/{$jobId}" => Http::response(['job' => ['status' => JobStatus::PENDING->value]], 200),
    ]);

    Redash::queries()->getResult($id, [], 0, 1);

})->throws(Exception::class, 'Job still in progress');
