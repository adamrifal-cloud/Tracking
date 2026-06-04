<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    /**
     * Show the Vendor Dashboard.
     */
    public function index()
    {
        $vendor = Auth::user();

        // Dummy data for vendor dashboard
        $documentStatus = 'Pending Verification';
        
        $suppliers = [
            ['name' => 'PT. Logistik Cepat', 'contact' => '0812345678', 'status' => 'Active'],
            ['name' => 'CV. Roda Tiga Mandiri', 'contact' => '0898765432', 'status' => 'Inactive'],
        ];

        return view('vendor.dashboard', compact(
            'vendor',
            'documentStatus',
            'suppliers'
        ));
    }
}
