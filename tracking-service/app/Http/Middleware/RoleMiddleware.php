<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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

        // Check status (only active users can access dashboard, but let inactive driver access dashboard to fill profile)
        if ($user->status === User::STATUS_SUSPENDED) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['status' => 'Akun Anda ditangguhkan. Silakan hubungi admin.']);
        }

        if ($user->status === User::STATUS_INACTIVE && !$user->isDriver()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['status' => 'Akun Anda dinonaktifkan.']);
        }

        return $next($request);
    }
}
