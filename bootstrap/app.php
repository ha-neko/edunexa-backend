<?php

// ============================================================
// bootstrap/app.php — Laravel 13
// ============================================================

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // REGISTER Spatie middleware aliases safely without dropping 'web' or 'api'
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'scanner.auth'       => \App\Http\Middleware\ScannerAuthenticate::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Semua exception dikembalikan sebagai JSON untuk API
        $exceptions->render(function (\Throwable $e, Request $request) {

            // 401 - Unauthenticated (Standard Laravel Auth)
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }

            // ── ADDED: JWT SPECIFIC EXCEPTIONS ───────────────────────
            // Token has expired
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'message' => 'Token sudah kedaluwarsa. Silakan login ulang.',
                ], 401);
            }

            // Token is manipulated, broken, or completely invalid
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'message' => 'Token tidak valid. Akses ditolak.',
                ], 401);
            }

            // Token was never sent in the Authorization header
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    'message' => 'Token tidak ditemukan pada request header.',
                ], 401);
            }
            // ─────────────────────────────────────────────────────────

            // 422 - Validation
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Data tidak valid.',
                    'errors'  => $e->errors(),
                ], 422);
            }

            // 403 - Spatie unauthorized role/permission
            if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki izin.',
                ], 403);
            }

            // 404 - Model not found
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            // 500 - Generic (sembunyikan detail di production)
            if (! config('app.debug')) {
                return response()->json([
                    'message' => 'Terjadi kesalahan pada server.',
                ], 500);
            }
        });
    })->create();
