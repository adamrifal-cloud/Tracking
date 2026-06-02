<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display the Driver Portal dashboard with tasks and stats.
     */
    public function index()
    {
        $driverIdString = 'Driver-' . Auth::id();

        // 1. Active Tasks (Assigned to this driver, not completed yet)
        $activeTasks = DB::table('orders')
            ->join('trackings', 'orders.order_id', '=', 'trackings.order_id')
            ->where('trackings.driver_id', '=', $driverIdString)
            ->where('trackings.status', 'NOT LIKE', '%diterima%')
            ->where('trackings.status', 'NOT LIKE', '%selesai%')
            ->where('trackings.status', 'NOT LIKE', '%delivered%')
            ->select('orders.*', 'trackings.status as tracking_status', 'trackings.latitude', 'trackings.longitude')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // 2. Completed Tasks (Assigned to this driver, completed/delivered)
        $completedTasks = DB::table('orders')
            ->join('trackings', 'orders.order_id', '=', 'trackings.order_id')
            ->where('trackings.driver_id', '=', $driverIdString)
            ->where(function($query) {
                $query->where('trackings.status', 'LIKE', '%diterima%')
                      ->orWhere('trackings.status', 'LIKE', '%selesai%')
                      ->orWhere('trackings.status', 'LIKE', '%delivered%');
            })
            ->select('orders.*', 'trackings.status as tracking_status')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // 3. Available Tasks (Paid orders, not assigned to this driver yet, and not completed)
        $availableTasks = DB::table('orders')
            ->join('trackings', 'orders.order_id', '=', 'trackings.order_id')
            ->where('orders.payment_status', 'PAID')
            ->where(function($query) use ($driverIdString) {
                $query->whereNull('trackings.driver_id')
                      ->orWhere('trackings.driver_id', '!=', $driverIdString);
            })
            ->where('trackings.status', 'NOT LIKE', '%diterima%')
            ->where('trackings.status', 'NOT LIKE', '%selesai%')
            ->where('trackings.status', 'NOT LIKE', '%delivered%')
            ->select('orders.*', 'trackings.status as tracking_status', 'trackings.driver_id as current_driver_id')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        return view('driver.dashboard', compact('activeTasks', 'completedTasks', 'availableTasks'));
    }

    /**
     * Claim an available paid shipping task.
     */
    public function claimTask(Request $request, $order_id)
    {
        $driverIdString = 'Driver-' . Auth::id();

        // Update driver_id in trackings
        $updated = DB::table('trackings')
            ->where('order_id', $order_id)
            ->update([
                'driver_id' => $driverIdString,
                'status' => 'Driver Terpilih - Bersiap Meluncur',
                'terakhir_diupdate' => now(),
                'updated_at' => now()
            ]);

        if ($updated) {
            // Fetch order info to create a customer notification
            $order = DB::table('orders')->where('order_id', $order_id)->first();
            if ($order) {
                DB::table('notifications')->insert([
                    'user_id' => $order->user_id,
                    'title' => 'Kurir Ditugaskan',
                    'body' => "Kurir " . Auth::user()->name . " telah mengambil tugas pengiriman #{$order->order_id} Anda dan sedang bersiap meluncur.",
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            return redirect()->route('driver.dashboard')->with('success', 'Tugas berhasil diambil!');
        }

        return redirect()->route('driver.dashboard')->with('error', 'Gagal mengambil tugas, silakan coba lagi.');
    }

    /**
     * Update the shipment status.
     */
    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|string|max:100'
        ]);

        $status = $request->input('status');
        $driverIdString = 'Driver-' . Auth::id();

        $updated = DB::table('trackings')
            ->where('order_id', $order_id)
            ->where('driver_id', $driverIdString)
            ->update([
                'status' => $status,
                'terakhir_diupdate' => now(),
                'updated_at' => now()
            ]);

        if ($updated) {
            $order = DB::table('orders')->where('order_id', $order_id)->first();
            if ($order) {
                // Determine appropriate notification title based on status
                $title = 'Status Pengiriman Diperbarui';
                if (stripos($status, 'selesai') !== false || stripos($status, 'diterima') !== false || stripos($status, 'delivered') !== false) {
                    $title = 'Paket Selesai Dikirim';
                }

                DB::table('notifications')->insert([
                    'user_id' => $order->user_id,
                    'title' => $title,
                    'body' => "Paket #{$order->order_id} Anda berstatus: {$status}.",
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'status' => 'Success',
                'message' => 'Status pengiriman berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'message' => 'Gagal memperbarui status pengiriman.'
        ], 500);
    }

    /**
     * Update the driver's current coordinates.
     */
    public function updateLocation(Request $request, $order_id)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $driverIdString = 'Driver-' . Auth::id();

        $updated = DB::table('trackings')
            ->where('order_id', $order_id)
            ->where('driver_id', $driverIdString)
            ->update([
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'terakhir_diupdate' => now(),
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Lokasi GPS pengiriman berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'message' => 'Gagal memperbarui lokasi GPS.'
        ], 500);
    }
}
