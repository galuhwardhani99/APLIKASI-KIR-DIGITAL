<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Dipakai di route untuk membatasi akses berdasarkan role.
     *
     * Contoh penggunaan di web.php:
     *   Route::middleware(['auth', 'role:admin'])->group(function () { ... });
     */
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk ' . $role . '.');
        }

        return $next($request);
    }
}
