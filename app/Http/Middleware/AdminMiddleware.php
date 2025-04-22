<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Debugging: Check if user is authenticated and log their role
        if (Auth::check()) {
            Log::info('User logged in:', ['user' => Auth::user()->role]);
        }

        // Pastikan user sudah login dan memiliki peran admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect ke halaman utama jika bukan admin
        Log::warning('User is not admin or not authenticated');
        return redirect('/');
    }
}
