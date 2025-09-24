<?php

declare(strict_types=1);

namespace Igorsgm\Redash\Resources;

use Igorsgm\Redash\Resources\Concerns\HasParams;
use Illuminate\Support\Facades\Http;

class QueryResults
{
    use HasParams;

    /**
     * Returns a query result
     * Appending a filetype of .csv or .json to this request will return a downloadable file.
     * If you append your api_key in the query string, this link will work for non-logged-in users.
     *
     * @return array
     */
    public function get(int|string $id)
    {
        return Http::redash($this->params)->get("/api/query_results/{$id}")->json();
    }
}
