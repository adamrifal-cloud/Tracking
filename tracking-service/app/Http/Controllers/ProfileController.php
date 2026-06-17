<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'license_number' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'license_number' => $request->license_number,
            'vehicle_number' => $request->vehicle_number,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Profil berhasil diperbarui!',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'license_number' => $user->license_number,
                'vehicle_number' => $user->vehicle_number,
            ]
        ], 200);
    }
}
