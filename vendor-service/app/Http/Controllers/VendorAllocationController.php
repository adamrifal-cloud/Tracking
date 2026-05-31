<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendDriverAllocation;

class VendorAllocationController extends Controller
{
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'driver_id' => 'required|integer',
            'driver_name' => 'required|string',
            'vendor_name' => 'required|string',
        ]);

        dispatch(new SendDriverAllocation($validated))->onQueue('driver-allocations');

        return response()->json([
            'status' => 'Success',
            'message' => 'Driver berhasil dialokasikan dan data dikirim ke Tracking Service!'
        ], 200);
    }
}
