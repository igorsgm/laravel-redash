<?php

declare(strict_types=1);

use Igorsgm\Redash\Facades\Redash;
use Illuminate\Support\Facades\Http;

it('gets a query result by id', function () {
    $id = 1;

    Http::fake([
        "*/query_results/{$id}" => Http::response(['id' => $id], 200),
    ]);

    $response = Redash::queryResults()->get($id);

    expect($response)->toBeArray()->and($response['id'])->toBe($id);
});
