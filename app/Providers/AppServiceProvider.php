<?php

namespace App\Providers;

use App\Contracts\AuthRepository;
use App\Contracts\DashboardRepository;
use App\Contracts\DepartmentRepository;
use App\Contracts\EmployeeRepository;
use App\Contracts\ProfileRepository;
use App\Contracts\UserRepository;
use App\Enums\Role;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Observers\DepartmentObserver;
use App\Observers\EmployeeObserver;
use App\Repositories\EloquentAuthRepository;
use App\Repositories\EloquentDashboardRepository;
use App\Repositories\EloquentDepartmentRepository;
use App\Repositories\EloquentEmployeeRepository;
use App\Repositories\EloquentProfileRepository;
use App\Repositories\EloquentUserRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthRepository::class,
            EloquentAuthRepository::class
        );

        $this->app->bind(
            EmployeeRepository::class,
            EloquentEmployeeRepository::class
        );

        $this->app->bind(
            DepartmentRepository::class,
            EloquentDepartmentRepository::class
        );

        $this->app->bind(
            UserRepository::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            ProfileRepository::class,
            EloquentProfileRepository::class
        );

        $this->app->bind(
            DashboardRepository::class,
            EloquentDashboardRepository::class
        );
    }

    public function boot(): void
    {

        Route::bind('user', fn (string $value) => User::where('username', $value)->firstOrFail());
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
        $isAdmin = fn (User $user): bool => $user->role === Role::Admin;

        Gate::define('manage-departments', $isAdmin);
        Gate::define('manage-employees', $isAdmin);
        Gate::define('manage-users', $isAdmin);
        Gate::define('manage-reports', $isAdmin);
    }
}
