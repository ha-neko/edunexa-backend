<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Pegawai;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;

// =====================
// CUSTOMER ROUTES
// =====================
Route::prefix('customer')->group(function () {

    // Public
    Route::prefix('auth')->group(function () {
        Route::post('/register',              [Customer\AuthController::class, 'register']);
        Route::post('/login',                 [Customer\AuthController::class, 'login']);
        Route::post('/verify-email',          [Customer\AuthController::class, 'verifyEmail']);
        Route::post('/verify-email/resend',   [Customer\AuthController::class, 'resendOtp']);
        Route::post('/forgot-password',       [Customer\PasswordResetController::class, 'forgotPassword']);
        Route::post('/forgot-password/verify',[Customer\PasswordResetController::class, 'verifyOtp']);
        Route::post('/forgot-password/resend',[Customer\PasswordResetController::class, 'resendOtp']);
        Route::post('/forgot-password/reset', [Customer\PasswordResetController::class, 'resetPassword']);
    });

    // Protected
    Route::middleware(['auth:api', 'active', 'log.ip'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('/me',      [Customer\AuthController::class, 'me']);
            Route::post('/logout', [Customer\AuthController::class, 'logout']);
            Route::post('/refresh',[Customer\AuthController::class, 'refresh']);
        });

        Route::prefix('user')->group(function () {
            Route::post('/profile',          [Customer\ProfileController::class, 'update']);
            Route::post('/profile-photo',    [Customer\ProfileController::class, 'updatePhoto']);
            Route::post('/change-password',  [Customer\ProfileController::class, 'changePassword']);
        });

        Route::apiResource('user-addresses', \App\Http\Controllers\Api\UserAddressController::class);
    });
});

// =====================
// PEGAWAI ROUTES
// =====================
Route::prefix('pegawai')->group(function () {

    // Public (Bisa diakses tanpa login)
    Route::prefix('auth')->group(function () {
        Route::post('/login',                  [Pegawai\AuthController::class, 'login']);
        Route::post('/verify-pin',             [Pegawai\AuthController::class, 'verifyPin']);
        Route::post('/verify-pin/resend',      [Pegawai\AuthController::class, 'resendPin']);
        Route::post('/forgot-password',        [Pegawai\PasswordResetController::class, 'forgotPassword']);
        Route::post('/forgot-password/verify', [Pegawai\PasswordResetController::class, 'verifyOtp']);
        Route::post('/forgot-password/resend', [Pegawai\PasswordResetController::class, 'resendOtp']);
        Route::post('/forgot-password/reset',  [Pegawai\PasswordResetController::class, 'resetPassword']);
    });

    // Protected (Wajib Login/Pakai Token)
    Route::middleware(['auth:api', 'active', 'log.ip'])->group(function () {
        
        // --- FITUR ABSENSI SEPTIAN ---
        Route::prefix('attendance')->group(function () {
            Route::post('/in',      [\App\Http\Controllers\AttendanceController::class, 'store']);   // Untuk Absen Masuk
            Route::get('/history',  [\App\Http\Controllers\AttendanceController::class, 'index']);   // Untuk Liat Riwayat
        });

        // Auth Pegawai
        Route::prefix('auth')->group(function () {
            Route::get('/me',      [Pegawai\AuthController::class, 'me']);
            Route::post('/logout', [Pegawai\AuthController::class, 'logout']);
            Route::post('/refresh', [Pegawai\AuthController::class, 'refresh']);
        });

        // Profile Pegawai
        Route::prefix('user')->group(function () {
            Route::post('/profile',         [Pegawai\ProfileController::class, 'update']);
            Route::post('/profile-photo',   [Pegawai\ProfileController::class, 'updatePhoto']);
            Route::post('/change-password', [Pegawai\ProfileController::class, 'changePassword']);
            Route::post('/change-pin',      [Pegawai\ProfileController::class, 'changePin']);
        });
    });
});

// =====================
// ADMIN ROUTES
// =====================
Route::prefix('admin')->group(function () {

    // Public
    Route::prefix('auth')->group(function () {
        Route::post('/login',                 [Admin\AuthController::class, 'login']);
        Route::post('/verify-pin',            [Admin\AuthController::class, 'verifyPin']);
        Route::post('/verify-pin/resend',     [Admin\AuthController::class, 'resendPin']);
        Route::post('/forgot-password',       [Admin\PasswordResetController::class, 'forgotPassword']);
        Route::post('/forgot-password/verify',[Admin\PasswordResetController::class, 'verifyOtp']);
        Route::post('/forgot-password/resend',[Admin\PasswordResetController::class, 'resendOtp']);
        Route::post('/forgot-password/reset', [Admin\PasswordResetController::class, 'resetPassword']);
    });

    // Protected
    Route::middleware(['auth:api', 'active', 'log.ip'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('/me',      [Admin\AuthController::class, 'me']);
            Route::post('/logout', [Admin\AuthController::class, 'logout']);
            Route::post('/refresh',[Admin\AuthController::class, 'refresh']);
        });

        Route::prefix('user')->group(function () {
            Route::post('/profile',         [\App\Http\Controllers\Api\ProfileController::class, 'update']);
            Route::post('/profile-photo',   [\App\Http\Controllers\Api\ProfileController::class, 'updatePhoto']);
            Route::post('/change-password', [\App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
            Route::post('/change-pin',      [Admin\ProfileController::class, 'changePin']);
        });

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/',              [UserController::class, 'index']);
            Route::get('/{id}',          [UserController::class, 'show']);
            Route::post('/',             [UserController::class, 'store']);
            Route::put('/{id}',          [UserController::class, 'update']);
            Route::delete('/{id}',       [UserController::class, 'destroy']);
            Route::post('/{id}/restore', [UserController::class, 'restore']);
            Route::post('/{id}/roles',        [RoleController::class, 'assignToUser']);
            Route::delete('/{id}/roles',      [RoleController::class, 'removeFromUser']);
            Route::post('/{id}/permissions',  [PermissionController::class, 'assignToUser']);
        });

        // Employee Management
        Route::prefix('employees')->group(function () {
            Route::get('/',                  [Admin\EmployeeController::class, 'index']);
            Route::get('/{id}',              [Admin\EmployeeController::class, 'show']);
            Route::post('/',                 [Admin\EmployeeController::class, 'store']);
            Route::put('/{id}',              [Admin\EmployeeController::class, 'update']);
            Route::delete('/{id}',           [Admin\EmployeeController::class, 'destroy']);
            Route::post('/{id}/restore',     [Admin\EmployeeController::class, 'restore']);
            Route::put('/{id}/status',       [Admin\EmployeeController::class, 'updateStatus']);
            Route::post('/{id}/generate-pin',[Admin\EmployeeController::class, 'generatePin']);
        });

        // Driver Management
        Route::prefix('drivers')->group(function () {
            Route::get('/',              [Admin\DriverController::class, 'index']);
            Route::get('/{id}',          [Admin\DriverController::class, 'show']);
            Route::post('/',             [Admin\DriverController::class, 'store']);
            Route::put('/{id}',          [Admin\DriverController::class, 'update']);
            Route::delete('/{id}',       [Admin\DriverController::class, 'destroy']);
            Route::post('/{id}/restore', [Admin\DriverController::class, 'restore']);
            Route::put('/{id}/status',   [Admin\DriverController::class, 'updateStatus']);
        });

        // Role & Permission
        Route::prefix('roles')->group(function () {
            Route::get('/',        [RoleController::class, 'index']);
            Route::post('/',       [RoleController::class, 'store']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/',        [PermissionController::class, 'index']);
            Route::post('/',       [PermissionController::class, 'store']);
            Route::delete('/{id}', [PermissionController::class, 'destroy']);
        });
    });
});
