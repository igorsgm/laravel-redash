<?php

declare(strict_types=1);

namespace Igorsgm\Redash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Igorsgm\Redash\Redash
 */
class Redash extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Igorsgm\Redash\Redash::class;
    }
}
