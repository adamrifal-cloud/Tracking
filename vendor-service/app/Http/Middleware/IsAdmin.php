<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_ADMIN) {
            return $next($request);
        }
        return redirect()->route('admin.login')->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini.']);
    }
}
