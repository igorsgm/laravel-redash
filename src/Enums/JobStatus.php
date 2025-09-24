<?php

declare(strict_types=1);

namespace Igorsgm\Redash\Enums;

enum JobStatus: int
{
    case PENDING = 1;
    case STARTED = 2;
    case SUCCESS = 3;
    case FAILURE = 4;
    case CANCELLED = 5;
}
