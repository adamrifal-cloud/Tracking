<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If the route allows multiple roles like "role:CUSTOMER,DRIVER"
        $roles = explode(',', $role);

        if (!$user->hasRole($roles)) {
            // Unauthorized access to this role section
            return abort(403, 'Unauthorized action. You do not have the required role.');
        }

        // Check status (only active users can access dashboard)
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['status' => 'Your account is inactive or suspended.']);
        }

        return $next($request);
    }
}
