<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use App\Models\Notification;
use App\Events\OrderLocationUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display the Driver Portal dashboard with tasks and stats.
     */
    public function index()
    {
        $driverIdString = 'Driver-' . Auth::id();

        // 1. Pending & Active Tasks (Assigned to this driver, not completed yet)
        $pendingTasks = Order::whereHas('tracking', function($query) use ($driverIdString) {
            $query->where('driver_id', $driverIdString)
                  ->where('status', 'NOT LIKE', '%diterima%')
                  ->where('status', 'NOT LIKE', '%selesai%')
                  ->where('status', 'NOT LIKE', '%delivered%');
        })
        ->with('tracking')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($order) {
            $order->tracking_status = $order->tracking->status;
            return $order;
        });

        // 2. Active Tasks (Assigned to this driver, confirmed, not completed yet)
        $activeTasks = Order::whereHas('tracking', function($query) use ($driverIdString) {
            $query->where('driver_id', $driverIdString)
                  ->where('status', 'NOT LIKE', '%diterima%')
                  ->where('status', 'NOT LIKE', '%selesai%')
                  ->where('status', 'NOT LIKE', '%delivered%')
                  ->where('status', '!=', 'Menunggu Konfirmasi Driver');
        })
        ->with('tracking')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($order) {
            $order->tracking_status = $order->tracking->status;
            $order->latitude = $order->tracking->latitude;
            $order->longitude = $order->tracking->longitude;
            return $order;
        });

        // 3. Completed Tasks (Assigned to this driver, completed/delivered)
        $completedTasks = Order::whereHas('tracking', function($query) use ($driverIdString) {
            $query->where('driver_id', $driverIdString)
                  ->where(function($q) {
                      $q->where('status', 'LIKE', '%diterima%')
                        ->orWhere('status', 'LIKE', '%selesai%')
                        ->orWhere('status', 'LIKE', '%delivered%');
                  });
        })
        ->with('tracking')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($order) {
            $order->tracking_status = $order->tracking->status;
            return $order;
        });

        // 4. Available Tasks (No longer used in Absolute Admin Allocation)
        $availableTasks = collect();

        return view('driver.dashboard', compact('pendingTasks', 'activeTasks', 'completedTasks', 'availableTasks'));
    }

    /**
     * Accept a pending task assigned by Admin.
     */
    public function acceptTask(Request $request, $order_id)
    {
        $driverIdString = 'Driver-' . Auth::id();

        // Update tracking status to active
        $updated = Tracking::where('order_id', $order_id)
            ->where('driver_id', $driverIdString)
            ->where('status', 'Menunggu Konfirmasi Driver')
            ->update([
                'status' => 'Driver Terpilih - Bersiap Meluncur',
                'terakhir_diupdate' => now(),
            ]);

        if ($updated) {
            $order = Order::where('order_id', $order_id)->first();
            if ($order) {
                // Send notification to customer/user that driver accepted the task
                Notification::create([
                    'user_id' => $order->user_id,
                    'title' => 'Kurir Ditugaskan',
                    'body' => "Kurir " . Auth::user()->name . " telah mengonfirmasi pengiriman #{$order->order_id} Anda dan sedang bersiap meluncur.",
                    'read_at' => null,
                ]);
            }

            // Dispatch WebSocket Event (OrderLocationUpdated)
            $tracking = Tracking::where('order_id', $order_id)->first();
            if ($tracking) {
                $stageAndEta = $this->getTrackingStageAndEta($tracking->status, $tracking->updated_at);
                event(new OrderLocationUpdated(
                    $tracking->order_id,
                    Auth::user()->name,
                    $tracking->status,
                    $tracking->latitude ?? -6.200000,
                    $tracking->longitude ?? 106.816666,
                    $stageAndEta['stage'],
                    $stageAndEta['eta'],
                    $tracking->updated_at->toIso8601String()
                ));
            }

            return response()->json([
                'status' => 'Success',
                'message' => 'Tugas pengiriman berhasil diterima!'
            ]);
        }

        return response()->json([
            'status' => 'Error',
            'message' => 'Gagal menerima tugas pengiriman.'
        ], 500);
    }

    /**
     * Claim an available paid shipping task (Disabled in Absolute Admin Allocation).
     */
    public function claimTask(Request $request, $order_id)
    {
        return redirect()->route('driver.dashboard')->with('error', 'Fitur klaim mandiri dinonaktifkan. Seluruh tugas ditunjuk langsung oleh Admin.');
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

        $updated = Tracking::where('order_id', $order_id)
            ->where('driver_id', $driverIdString)
            ->update([
                'status' => $status,
                'terakhir_diupdate' => now(),
            ]);

        if ($updated) {
            $order = Order::where('order_id', $order_id)->first();
            if ($order) {
                // Determine appropriate notification title based on status
                $title = 'Status Pengiriman Diperbarui';
                if (stripos($status, 'selesai') !== false || stripos($status, 'diterima') !== false || stripos($status, 'delivered') !== false) {
                    $title = 'Paket Selesai Dikirim';
                }

                Notification::create([
                    'user_id' => $order->user_id,
                    'title' => $title,
                    'body' => "Paket #{$order->order_id} Anda berstatus: {$status}.",
                    'read_at' => null,
                ]);
            }

            // Dispatch WebSocket Event
            $tracking = Tracking::where('order_id', $order_id)->first();
            if ($tracking) {
                $stageAndEta = $this->getTrackingStageAndEta($tracking->status, $tracking->updated_at);
                event(new OrderLocationUpdated(
                    $tracking->order_id,
                    Auth::user()->name,
                    $tracking->status,
                    $tracking->latitude,
                    $tracking->longitude,
                    $stageAndEta['stage'],
                    $stageAndEta['eta'],
                    $tracking->updated_at->toIso8601String()
                ));
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

        $updated = Tracking::where('order_id', $order_id)
            ->where('driver_id', $driverIdString)
            ->update([
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'terakhir_diupdate' => now(),
            ]);

        if ($updated) {
            // Dispatch WebSocket Event
            $tracking = Tracking::where('order_id', $order_id)->first();
            if ($tracking) {
                $stageAndEta = $this->getTrackingStageAndEta($tracking->status, $tracking->updated_at);
                event(new OrderLocationUpdated(
                    $tracking->order_id,
                    Auth::user()->name,
                    $tracking->status,
                    $tracking->latitude,
                    $tracking->longitude,
                    $stageAndEta['stage'],
                    $stageAndEta['eta'],
                    $tracking->updated_at->toIso8601String()
                ));
            }

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

    /**
     * Map shipping status string to 4 stages and calculate smart ETA dynamically relative to current time.
     */
    private function getTrackingStageAndEta($status, $updatedAt)
    {
        $statusLower = strtolower($status);
        $now = time();
        
        $stage = 0; // 0: Dikemas, 1: Diperjalanan, 2: Kurir Menuju Lokasi, 3: Diterima
        $eta = '';
        
        if (strpos($statusLower, 'diterima') !== false || strpos($statusLower, 'selesai') !== false || strpos($statusLower, 'delivered') !== false) {
            $stage = 3;
            $eta = 'Paket telah diterima';
        } elseif (strpos($statusLower, 'menuju lokasi') !== false || strpos($statusLower, 'pick up') !== false) {
            $stage = 2;
            // Kurir menuju lokasi: ETA is 30 - 90 minutes from now
            $startEta = date('H:i', $now + 1800); // +30 mins
            $endEta = date('H:i', $now + 5400);   // +90 mins
            $eta = "Hari ini pukul {$startEta} - {$endEta}";
        } elseif (strpos($statusLower, 'perjalanan') !== false || strpos($statusLower, 'transit') !== false || strpos($statusLower, 'kirim') !== false || strpos($statusLower, 'driver terpilih') !== false || strpos($statusLower, 'meluncur') !== false) {
            $stage = 1;
            // Diperjalanan: ETA is today (e.g. 2 - 4 hours from now)
            $startEta = date('H:i', $now + 7200); // +2 hours
            $endEta = date('H:i', $now + 14400);  // +4 hours
            
            // If it's already late night (e.g. after 8 PM), ETA is tomorrow morning
            if (date('H', $now) >= 20) {
                $eta = "Besok pukul 09:00 - 11:30";
            } else {
                $eta = "Hari ini pukul {$startEta} - {$endEta}";
            }
        } else {
            // Default / Dikemas / Diproses
            $stage = 0;
            // Dikemas: ETA is tomorrow morning/afternoon
            $eta = "Besok pukul 10:00 - 12:30";
        }

        return [
            'stage' => $stage,
            'eta' => $eta
        ];
    }
}
