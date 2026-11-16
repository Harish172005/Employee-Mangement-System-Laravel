<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Employee;
use App\Observers\EmployeeObserver;
use App\Observers\DepartmentObserver;
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
        Employee::observe(EmployeeObserver::class);

        Department::observe(DepartmentObserver::class);

        // Pagination links use Bootstrap 5 markup instead of Tailwind
        Paginator::useBootstrapFive();

        // Available in every view as $appName
        View::share('appName', config('app.name'));

        // Gives every employees.* view a $departments list
        View::composer('employees.*', function ($view) {
            $view->with('departments', Department::query()->orderBy('name')->pluck('name', 'id'));
        });
    }
}