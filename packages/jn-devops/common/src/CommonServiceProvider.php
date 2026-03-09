<?php

namespace Homeful\Common;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Homeful\Common\Commands\CommonCommand;
use Spatie\LaravelPackageTools\Package;

class CommonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('common')
            ->hasCommand(CommonCommand::class);
    }
}
