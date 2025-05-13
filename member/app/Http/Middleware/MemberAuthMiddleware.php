<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'member') {
            return redirect()->route('login');
        }

        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response) {
            $response->header('Cache-Control','no-cache, no-store, must-revalidate')
                     ->header('Pragma','no-cache')
                     ->header('Expires','0');
        }
        
        return $response;
    }
}
