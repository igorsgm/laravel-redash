<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

it('configures the Http::redash() macro correctly', function () {
    $apiKey = 'test_api_key';
    $baseUrl = 'http://test.com';

    // Configure the HTTP client.
    Http::fake([
        $baseUrl.'/*' => Http::response(['foo' => 'bar'], 200),
    ]);

    // Call the redash macro.
    $response = Http::redash([
        'api_key' => $apiKey,
        'base_url' => $baseUrl,
    ])->get('/endpoint');

    // Check that the request was sent with the correct apiKey and baseUrl.
    Http::assertSent(function ($request) use ($apiKey, $baseUrl) {
        return $request->hasHeader('Authorization', 'Key '.$apiKey) &&
            $request->url() === mb_rtrim($baseUrl, '/').'/endpoint';
    });

    // Assert that the response was successful.
    expect($response->successful())->toBeTrue()
        ->and($response['foo'])->toBe('bar');
});
