<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Show the shipping order creation form.
     */
    public function create()
    {
        return view('customer.create_order');
    }

    /**
     * Store a newly created shipping order in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:50',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:50',
            'receiver_address' => 'required|string',
            'package_description' => 'required|string',
            'package_weight' => 'required|numeric|min:0.1',
        ]);

        // Generate unique order ID
        $orderId = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));

        // Simple pricing calculation: 10,000 IDR per kg
        $price = ceil($validated['package_weight']) * 10000;

        $order = Order::create([
            'order_id' => $orderId,
            'user_id' => Auth::id(),
            'sender_name' => $validated['sender_name'],
            'sender_phone' => $validated['sender_phone'],
            'sender_address' => $validated['sender_address'],
            'receiver_name' => $validated['receiver_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'receiver_address' => $validated['receiver_address'],
            'package_description' => $validated['package_description'],
            'package_weight' => $validated['package_weight'],
            'price' => $price,
            'payment_status' => 'UNPAID',
        ]);

        // Create a pending payment notification
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Pembayaran Tertunda',
            'body' => "Pesanan #{$order->order_id} berhasil dibuat. Silakan selesaikan pembayaran Anda.",
            'read_at' => null,
        ]);

        return redirect()->route('customer.payment', $order->order_id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Show the payment gateway simulation page.
     */
    public function showPayment($order_id)
    {
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        if ($order->payment_status === 'PAID') {
            return redirect()->route('track')->with('info', 'Pesanan ini sudah dibayar.');
        }

        return view('customer.payment', compact('order'));
    }

    /**
     * Simulate the payment process (gerbang pembayaran callback simulation).
     */
    public function processPayment(Request $request, $order_id)
    {
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        if ($order->payment_status === 'PAID') {
            return redirect()->route('track')->with('info', 'Pesanan ini sudah dibayar.');
        }

        // Update payment status
        $order->update([
            'payment_status' => 'PAID'
        ]);

        // Initialize Tracking Service for this order with coordinates stored in database
        Tracking::create([
            'order_id' => $order->order_id,
            'driver_id' => 'Driver-' . mt_rand(10, 99), // Dummy driver assigned automatically
            'status' => 'Dikemas',
            'latitude' => -6.200000 + (mt_rand(-100, 100) / 10000),
            'longitude' => 106.816666 + (mt_rand(-100, 100) / 10000),
            'terakhir_diupdate' => now(),
        ]);

        // Add recent search history for quick tracking access
        \App\Models\RecentSearch::updateOrCreate(
            ['user_id' => Auth::id(), 'order_id' => $order->order_id],
            ['updated_at' => now()]
        );

        // Send payment success notification
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Pembayaran Berhasil',
            'body' => "Pembayaran untuk order #{$order->order_id} berhasil diterima. Paket Anda sedang dikemas.",
            'is_read' => false,
        ]);

        return redirect()->route('track')
            ->with('success', "Pembayaran untuk order #{$order->order_id} berhasil disimulasikan!");
    }

    /**
     * Cancel an unpaid shipping order.
     */
    public function cancel($order_id)
    {
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', Auth::id())
                      ->where('payment_status', 'UNPAID')
                      ->firstOrFail();

        // Update payment status to CANCELLED
        $order->update([
            'payment_status' => 'CANCELLED'
        ]);

        // Send cancellation notification
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Pesanan Dibatalkan',
            'body' => "Pesanan #{$order->order_id} berhasil dibatalkan.",
            'is_read' => false,
        ]);

        return redirect()->route('track')
            ->with('success', "Pesanan #{$order->order_id} berhasil dibatalkan.");
    }

    /**
     * Delete/Hide an order from history (only if PAID/completed or CANCELLED).
     */
    public function destroy($order_id)
    {
        $order = Order::where('order_id', $order_id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $isCompleted = false;
        if ($order->tracking) {
            $statusLower = strtolower($order->tracking->status);
            if (strpos($statusLower, 'diterima') !== false || strpos($statusLower, 'selesai') !== false || strpos($statusLower, 'delivered') !== false) {
                $isCompleted = true;
            }
        }

        if ($order->payment_status === 'CANCELLED' || $isCompleted) {
            // Delete related trackings
            Tracking::where('order_id', $order_id)->delete();
            
            // Delete the order
            $order->delete();

            return redirect()->route('track')->with('success', "Riwayat pengiriman #{$order_id} berhasil dihapus.");
        }

        return redirect()->route('track')->with('error', "Pesanan ini belum selesai atau dibatalkan, tidak dapat dihapus.");
    }
}
