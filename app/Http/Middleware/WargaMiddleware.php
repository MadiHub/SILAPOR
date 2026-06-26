<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class WargaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/auth')->with('error', 'Harus login dulu!');
        }

        if (Auth::user()->role !== 'warga') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}
