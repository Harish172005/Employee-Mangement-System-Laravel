<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web routes
|--------------------------------------------------------------------------
|  1. Public            login pages (guests only) and logout
|  2. Any logged-in     dashboard
|  3. Admin area        /admin/...   route names: admin.*
|  4. Employee area     /my/...      route names: profile.*
|  5. Fallback          unknown URLs show the 404 page
|
| Middleware used (the aliases "role" and "active" are registered in bootstrap/app.php):
|   guest       only logged-out visitors
|   auth        must be logged in (guests are sent to the login page)
|   active      the account status must still be "active"
|   role:admin  only users whose role is admin (anyone else gets a 403)
|   can:xxx     a gate defined in AppServiceProvider
|
| Order matters inside a list: auth first, because the others need to know who the user is.
*/

// ------------------------------------------------------------------
// 1. Public
// ------------------------------------------------------------------

// Everyone is sent to the dashboard; a guest is then redirected on to the login page by "auth".
Route::redirect('/', '/dashboard');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.attempt');

        // Forgot / reset password (build these in the password-reset step) belong here too:
        Route::get('/forgot-password', 'showForgotForm')->name('password.request');
        Route::post('/forgot-password', 'sendResetLink')->name('password.email');
        Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');   // this exact name is required
        Route::post('/reset-password', 'resetPassword')->name('password.store');
    });

    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
});




// ------------------------------------------------------------------
// 3. Admin area (logged in, active account, admin role)
// ------------------------------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin'])->group(function () {

    // Employees: the extra routes first, then the standard resource routes
    Route::get('/dashboard', DashboardController::class) ->name('dashboard');
    Route::controller(EmployeeController::class)->prefix('employees')->middleware('can:manage-employees')->name('employees.')->group(function () {
        Route::get('search', 'search')->name('search');                 // JSON for the live search
        Route::get('export', 'export')->name('export');                 // CSV download
        Route::post('{employee}/restore', 'restore')->whereNumber('employee')->name('restore');
        Route::delete('{employee}/force-delete', 'forceDelete')->whereNumber('employee')->name('force-delete');
    });
    Route::resource('employees', EmployeeController::class)->whereNumber('employee');

    // Departments
    Route::resource('departments', DepartmentController::class)->whereNumber('department');

    // Users: {user} is resolved by username (explicit binding in AppServiceProvider)
    Route::resource('users', UserController::class)->middleware('can:manage-users');

    // Reports: "role:admin" already protects the group; the gate is a second lock on this page
    Route::get('reports', ReportController::class)->middleware('can:manage-reports')->name('reports');
});



Route::prefix('my')->name('profile.')->middleware(['auth', 'active', 'role:employee'])->controller(ProfileController::class)->group(function () {
    Route::get('profile', 'show')->name('show');
    Route::get('profile/edit', 'edit')->name('edit');
    Route::put('profile', 'update')->name('update');
    Route::get('department', 'department')->name('department');
    Route::get('password', 'editPassword')->name('password.edit');
    Route::put('password', 'updatePassword')->name('password.update');
});

Route::fallback(fn () => abort(404));