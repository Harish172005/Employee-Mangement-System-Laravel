<?php

use App\Http\Controllers\{AuthController, DashboardController, EmployeeController,
    DepartmentController, UserController, ProfileController, ReportController};
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Guest routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Admin group (auth + role middleware will be added in Module 8)
Route::prefix('admin')->name('admin.')->group(function () {
    // Custom routes MUST come before the resource, or {employee} swallows them
    Route::get('employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::post('employees/{employee}/restore', [EmployeeController::class, 'restore'])->whereNumber('employee')->name('employees.restore');
    Route::delete('employees/{employee}/force-delete', [EmployeeController::class, 'forceDelete'])
        ->whereNumber('employee')->middleware('auth')->name('employees.force-delete');

    Route::resource('employees', EmployeeController::class)->whereNumber('employee');
    Route::resource('departments', DepartmentController::class)->whereNumber('department');
    Route::resource('users', UserController::class);
    Route::get('reports', ReportController::class)->name('reports');
});

// Employee self-service group
Route::prefix('my')->name('profile.')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('update');
    Route::get('department', [ProfileController::class, 'department'])->name('department');
    Route::get('password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// Unknown URLs
Route::fallback(fn () => response('Page not found', 404));