<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hostels', [HostelController::class, 'index'])->name('hostels.index');
Route::get('/hostels/{hostel:slug}', [HostelController::class, 'show'])->name('hostels.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/managers/{user}/approve', [AdminController::class, 'approveManager'])->name('managers.approve');
        Route::post('/managers/{user}/suspend', [AdminController::class, 'suspendManager'])->name('managers.suspend');
    });

    Route::middleware('role:manager')->prefix('manager')->name('manager.')->group(function (): void {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    });
});
