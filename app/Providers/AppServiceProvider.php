<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\ProjectObserver;
use App\Observers\PropertyObserver;
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
        Project::observe(ProjectObserver::class);
        Property::observe(PropertyObserver::class);
        //
        Gate::define('viewPulse', function (User $user) {
//            return $user->isAdmin();
            return true;
        });
    }
}
