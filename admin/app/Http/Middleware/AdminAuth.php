<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin')) {
            return redirect()->route('admin.login')->with('error', 'You must be logged in.');
        }

        $response = $next($request);

        return $response->header('Cache-Control','no-cache, no-store, must-revalidate')
                        ->header('Pragma','no-cache')
                        ->header('Expires','0');
    }
}
