<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_VENDOR) {
            return $next($request);
        }
        return redirect()->route('vendor.login')->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini.']);
    }
}
