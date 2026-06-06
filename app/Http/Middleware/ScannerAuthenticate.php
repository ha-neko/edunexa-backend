<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScannerAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow authenticated web users (admin/guru scanning via browser)
        if (auth('api')->check()) {
            return $next($request);
        }

        $secret = config('app.scanner_secret');

        if ($secret === null || $secret === '') {
            return $next($request);
        }

        $header = $request->header('X-Scanner-Secret');

        if (! $header || ! hash_equals($secret, $header)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized scanner device.',
            ], 401);
        }

        return $next($request);
    }
}
