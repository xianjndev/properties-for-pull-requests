<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\PropertyObserver;
use Homeful\Products\Models\Product;
use Homeful\Properties\Models\Property;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Product::observe(ProductObserver::class);
        Property::observe(PropertyObserver::class);
        //
        Gate::define('viewPulse', function (User $user) {
//            return $user->isAdmin();
            return true;
        });
    }
}
