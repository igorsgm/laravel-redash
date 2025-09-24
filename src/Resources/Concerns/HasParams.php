<?php

declare(strict_types=1);

namespace Igorsgm\Redash\Resources\Concerns;

use Igorsgm\Redash\Resources\Dashboards;
use Igorsgm\Redash\Resources\Jobs;
use Igorsgm\Redash\Resources\Queries;
use Igorsgm\Redash\Resources\QueryResults;

trait HasParams
{
    protected $params;

    /**
     * @param  mixed  $params
     * @return Dashboards|Jobs|Queries|QueryResults
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
