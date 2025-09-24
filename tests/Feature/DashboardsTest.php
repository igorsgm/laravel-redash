<?php

declare(strict_types=1);

use Igorsgm\Redash\Facades\Redash;
use Illuminate\Support\Facades\Http;

it('gets all dashboards', function () {
    Http::fake([
        '*/dashboards' => Http::response(['results' => []], 200),
    ]);

    $response = Redash::dashboards()->all();

    expect($response)->toBeArray()->and($response['results'])->toBeArray();
});

it('gets a dashboard by slug', function () {
    $slug = 'dashboard-1';

    Http::fake([
        "*/dashboards/{$slug}" => Http::response(['slug' => $slug], 200),
    ]);

    $response = Redash::dashboards()->get($slug);

    expect($response)->toBeArray()->and($response['slug'])->toBe($slug);
});

it('creates a new dashboard', function () {
    $data = ['name' => 'Dashboard 1'];

    Http::fake([
        '*/dashboards' => Http::response(['slug' => 'dashboard-1'], 200),
    ]);

    $response = Redash::dashboards()->create($data);

    expect($response)->toBeArray()->and($response['slug'])->toBe('dashboard-1');
});

it('updates a dashboard', function () {
    $slug = 'dashboard-1';
    $data = ['name' => 'Dashboard 2'];

    Http::fake([
        "*/dashboards/{$slug}" => Http::response(['slug' => $slug], 200),
    ]);

    $response = Redash::dashboards()->update($slug, $data);

    expect($response)->toBeArray()->and($response['slug'])->toBe($slug);
});

it('deletes a dashboard', function () {
    $slug = 'dashboard-1';

    Http::fake([
        "*/dashboards/{$slug}" => Http::response(null, 200),
    ]);

    $response = Redash::dashboards()->delete($slug);

    expect($response)->toBeNull();
});
