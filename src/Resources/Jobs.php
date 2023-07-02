<?php

namespace Igorsgm\Redash\Resources;

use Igorsgm\Redash\Resources\Concerns\HasParams;
use Illuminate\Support\Facades\Http;

class Jobs
{
    use HasParams;

    /**
     * Returns a query task result (job)
     * When status is success, the job will include a query_result_id
     *
     * @see \Igorsgm\Redash\Enums\JobStatus for possible statuses
     *
     * @return array
     */
    public function get(int|string $id)
    {
        return Http::redash($this->params)->get("/api/jobs/{$id}")->json();
    }
}
