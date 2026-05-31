<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
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

        // Kembalikan data status ke aplikasi pelanggan (Frontend/Mobile)
        return response()->json([
            'status' => 'Success',
            'data' => [
                'order_id' => $tracking->order_id,
                'driver_id' => $tracking->driver_id,
                'status_pengiriman' => $tracking->status,
                'terakhir_diupdate' => $tracking->updated_at
            ]
        ], 200);
    }
}
