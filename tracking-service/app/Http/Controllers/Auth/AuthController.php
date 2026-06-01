<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the main choice screen.
     */
    public function showChoiceScreen()
    {
        return view('auth.choice');
    }

    /**
     * Show Customer Login Form
     */
    public function showCustomerLogin()
    {
        return view('auth.customer-login');
    }

    /**
     * Show Customer Registration Form
     */
    public function showCustomerRegister()
    {
        return view('auth.customer-register');
    }

    /**
     * Show Driver Login Form
     */
    public function showDriverLogin()
    {
        return view('auth.driver-login');
    }

    /**
     * Handle Login (Shared logic but validates roles based on intended section)
     */
    public function login(Request $request, $expectedRole)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Validate that the user logging in matches the portal they are trying to access
            if ($user->role !== $expectedRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => "This account is not registered as a $expectedRole.",
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role
            return redirect()->intended(strtolower($user->role) . '/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle Customer Login Submission
     */
    public function customerLogin(Request $request)
    {
        return $this->login($request, User::ROLE_CUSTOMER);
    }

    /**
     * Handle Driver Login Submission
     */
    public function driverLogin(Request $request)
    {
        return $this->login($request, User::ROLE_DRIVER);
    }

    /**
     * Handle Customer Registration Submission
     */
    public function customerRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_CUSTOMER,
            'status' => User::STATUS_ACTIVE,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }

    /**
     * Handle Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('choice');
    }
}
