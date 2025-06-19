<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string[]  ...$userTypes
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$userTypes): Response
    {
        // Pastikan pengguna terautentikasi
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Cek apakah tipe pengguna sesuai dengan salah satu tipe yang diizinkan
        if (in_array(auth()->user()->type, $userTypes)) {
            return $next($request);
        }

        // Jika tidak memiliki izin, kembalikan respons error
        return response()->json(['message' => 'You do not have permission to access this page.'], 403);
    }
}
