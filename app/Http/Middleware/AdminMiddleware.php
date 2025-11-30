<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
      public function handle(Request $request, Closure $next)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        // Cek role admin (1 = admin)
        if (session('role') != 1) {
            abort(403, 'Akses khusus admin.');
        }

        return $next($request);
    }
}
