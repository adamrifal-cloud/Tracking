<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show the Admin Dashboard.
     */
    public function index()
    {
        // For demonstration, we calculate some dummy statistics
        $totalAllocations = 1284;
        $activeDrivers = 42;
        $queueStatus = 'Healthy';

        // Get a dummy list of pending vendors (just users with role VENDOR)
        // In a real app, there might be a specific status field
        $pendingVendors = User::where('role', User::ROLE_VENDOR)->get();

        return view('admin.dashboard', compact(
            'totalAllocations', 
            'activeDrivers', 
            'queueStatus',
            'pendingVendors'
        ));
    }
}
