<?php

namespace Igorsgm\Redash;

use Illuminate\Support\Facades\Http;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RedashServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-redash')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        Http::macro('redash', function (array $params = []) {
            $apiKey = data_get($params, 'api_key') ?: config('redash.api_key');
            $baseUrl = data_get($params, 'base_url') ?: config('redash.base_url');

            return Http::withToken($apiKey, 'Key')
                ->baseUrl(rtrim($baseUrl, '/'));
        });
    }
}
