<?php

declare(strict_types=1);

use Igorsgm\Redash\Facades\Redash;
use Illuminate\Support\Facades\Http;

it('gets a job by id', function () {
    $id = 1;

    Http::fake([
        "*/jobs/{$id}" => Http::response(['id' => $id], 200),
    ]);

    $response = Redash::jobs()->get($id);

    expect($response)->toBeArray()->and($response['id'])->toBe($id);
});
