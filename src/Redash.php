<?php

declare(strict_types=1);

namespace Igorsgm\Redash;

use Igorsgm\Redash\Resources\Dashboards;
use Igorsgm\Redash\Resources\Jobs;
use Igorsgm\Redash\Resources\Queries;
use Igorsgm\Redash\Resources\QueryResults;

class Redash
{
    public function queries(array $params = []): Queries
    {
        return resolve(Queries::class)->setParams($params);
    }

    public function queryResults(array $params = []): QueryResults
    {
        return resolve(QueryResults::class)->setParams($params);
    }

    public function jobs(array $params = []): Jobs
    {
        return resolve(Jobs::class)->setParams($params);
    }

    public function dashboards(array $params = []): Dashboards
    {
        return resolve(Dashboards::class)->setParams($params);
    }
}
