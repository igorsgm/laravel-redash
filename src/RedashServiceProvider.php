<?php

namespace Igorsgm\Redash;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Igorsgm\Redash\Commands\RedashCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-redash_table')
            ->hasCommand(RedashCommand::class);
    }
}
