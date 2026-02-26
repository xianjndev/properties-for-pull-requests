<?php

namespace Homeful\Products;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Homeful\Products\Commands\ProductsCommand;
use Spatie\LaravelPackageTools\Package;

class ProductsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('products')
            ->hasConfigFile(['products'])
            ->hasViews()
            ->hasMigration('create_products_table')
            ->hasCommand(ProductsCommand::class);
    }
}
