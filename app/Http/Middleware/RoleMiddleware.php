<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed $roles  Nama role atau beberapa role dipisah koma
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            // Belum login
            return redirect('/login');
        }

        // Cek apakah user punya salah satu role
        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Unauthorized'); // Bisa diganti redirect ke halaman lain
        }

        return $next($request);
    }
}
