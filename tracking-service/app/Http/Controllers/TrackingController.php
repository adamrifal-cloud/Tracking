<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index()
    {
        $recentSearches = [];
        $myOrders = [];
        
        if (auth()->check()) {
            $recentSearches = \App\Models\RecentSearch::where('user_id', auth()->id())
                                ->orderBy('updated_at', 'desc')
                                ->take(5)
                                ->get();
            $myOrders = \App\Models\Order::where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();
        }

        return view('customer.dashboard', compact('recentSearches', 'myOrders'));
    }

    public function checkStatus($order_id)
    {
        // Cari data tracking terbaru di database berdasarkan Order ID
        $tracking = DB::table('trackings')->where('order_id', $order_id)->first();

        if (!$tracking) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Nomor resi / Order ID tidak ditemukan.'
            ], 404);
        }

        // Save to recent searches if user is authenticated
        if (auth()->check()) {
            \App\Models\RecentSearch::updateOrCreate(
                ['user_id' => auth()->id(), 'order_id' => $tracking->order_id],
                ['updated_at' => now()]
            );
        }

        $stageAndEta = $this->getTrackingStageAndEta($tracking->status, $tracking->updated_at);

        // Kembalikan data status ke aplikasi pelanggan (Frontend/Mobile)
        return response()->json([
            'status' => 'Success',
            'data' => [
                'order_id' => $tracking->order_id,
                'driver_id' => $tracking->driver_id,
                'status_pengiriman' => $tracking->status,
                'terakhir_diupdate' => $tracking->updated_at,
                'stage' => $stageAndEta['stage'],
                'eta' => $stageAndEta['eta'],
                // Real GPS coordinates from database with a fallback if empty
                'lat' => $tracking->latitude ?? (-6.200000 + (mt_rand(-100, 100) / 10000)),
                'lng' => $tracking->longitude ?? (106.816666 + (mt_rand(-100, 100) / 10000))
            ]
        ], 200);
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
        } elseif (strpos($statusLower, 'menuju lokasi') !== false || strpos($statusLower, 'driver terpilih') !== false || strpos($statusLower, 'meluncur') !== false || strpos($statusLower, 'pick up') !== false) {
            $stage = 2;
            // Kurir menuju lokasi: ETA is 30 - 90 minutes from now
            $startEta = date('H:i', $now + 1800); // +30 mins
            $endEta = date('H:i', $now + 5400);   // +90 mins
            $eta = "Hari ini pukul {$startEta} - {$endEta}";
        } elseif (strpos($statusLower, 'perjalanan') !== false || strpos($statusLower, 'transit') !== false || strpos($statusLower, 'kirim') !== false) {
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

    /**
     * Delete a single recent search item from the database.
     */
    public function deleteRecentSearch($id)
    {
        $search = \App\Models\RecentSearch::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->first();
        if ($search) {
            $search->delete();
        }
        return redirect()->route('track')->with('success', 'Riwayat pencarian berhasil dihapus.');
    }

    /**
     * Clear all recent search items from the database for the user.
     */
    public function clearRecentSearches()
    {
        \App\Models\RecentSearch::where('user_id', auth()->id())->delete();
        return redirect()->route('track')->with('success', 'Semua riwayat pencarian berhasil dibersihkan.');
    }
}
