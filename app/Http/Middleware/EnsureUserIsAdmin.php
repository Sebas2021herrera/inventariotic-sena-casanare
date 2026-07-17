<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()?->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        return $next($request);
    }
}
