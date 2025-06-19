<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            Log::info('User type in middleware: ' . Auth::user()->type);
            if (Auth::user()->type === 'admin') { // Gunakan nilai 'admin'
                return $next($request);
            }
        }

        Log::warning('Unauthorized access attempt by user type: ' . (Auth::check() ? Auth::user()->type : 'guest'));
        return redirect('/')->withErrors(['message' => 'Anda tidak memiliki akses ke halaman ini.']);
    }
}
