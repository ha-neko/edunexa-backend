<?php

namespace App\Http\Middleware;

use App\Models\AuthLog;
use Closure;
use Illuminate\Http\Request;

class LogIpActivity
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        $user = auth()->user();
        if ($user) {
            AuthLog::create([
                'user_id'    => $user->id,
                'event'      => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
            ]);
        }
    }
}
