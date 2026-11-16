<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination links use Bootstrap 5 markup instead of Tailwind
        Paginator::useBootstrapFive();

        // Available in every view as $appName
        View::share('appName', config('app.name'));

        // Gives every employees.* view a $departments list
        View::composer('employees.*', function ($view) {
            $view->with('departments', [
                1 => 'IT',
                2 => 'HR',
                3 => 'Finance',
                4 => 'Sales',
                5 => 'Operations',
            ]);
        });
    }
}