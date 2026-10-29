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
|  1. Public            login and logout
|  2. Any logged-in     dashboard
|  3. Admin area        /admin/...   route names: admin.*
|  4. Employee area     /my/...      route names: profile.*
|  5. Fallback          unknown URLs show the 404 page
|
| Module 8 adds the middleware (one line per group, marked "Module 8" below):
|     public -> 'guest'      admin area -> ['auth', 'role:admin']      employee area -> 'auth'
*/

// ------------------------------------------------------------------
// 1. Public
// ------------------------------------------------------------------
Route::redirect('/', '/login');

Route::controller(AuthController::class)->group(function () {   // Module 8: ->middleware('guest') on the two login routes
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.attempt');
    Route::post('/logout', 'logout')->name('logout');
});

// ------------------------------------------------------------------
// 2. Any logged-in user
// ------------------------------------------------------------------
Route::get('/dashboard', DashboardController::class)->name('dashboard');   // Module 8: ->middleware('auth')

// ------------------------------------------------------------------
// 3. Admin area
// ------------------------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {   // Module 8: ->middleware(['auth', 'role:admin'])

    // Employees: the extra routes first, then the standard resource routes
    Route::controller(EmployeeController::class)->prefix('employees')->name('employees.')->group(function () {
        Route::get('search', 'search')->name('search');                 // JSON for the live search
        Route::get('export', 'export')->name('export');                 // CSV download
        Route::post('{employee}/restore', 'restore')->whereNumber('employee')->name('restore');
        Route::delete('{employee}/force-delete', 'forceDelete')->whereNumber('employee')->name('force-delete');
    });
    Route::resource('employees', EmployeeController::class)->whereNumber('employee');

    // Departments
    Route::resource('departments', DepartmentController::class)->whereNumber('department');

    // Users: {user} is resolved by username (explicit binding in AppServiceProvider)
    Route::resource('users', UserController::class);

    // Reports
    Route::get('reports', ReportController::class)->name('reports');
});

// ------------------------------------------------------------------
// 4. Employee self-service area
// ------------------------------------------------------------------
Route::prefix('my')->name('profile.')->controller(ProfileController::class)->group(function () {   // Module 8: ->middleware('auth')
    Route::get('profile', 'show')->name('show');
    Route::get('profile/edit', 'edit')->name('edit');
    Route::put('profile', 'update')->name('update');
    Route::get('department', 'department')->name('department');
    Route::get('password', 'editPassword')->name('password.edit');
    Route::put('password', 'updatePassword')->name('password.update');
});

// ------------------------------------------------------------------
// 5. Fallback: any unknown URL shows resources/views/errors/404.blade.php
// ------------------------------------------------------------------
Route::fallback(fn () => abort(404));