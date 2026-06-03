<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Return a list of recent notifications for the authenticated user.
     * Includes unread count.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'body', 'created_at', 'read_at']);

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // If user has no notifications, dynamically generate relevant/realistic notifications
        if ($notifications->count() === 0) {
            if ($user->role === 'DRIVER') {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Selamat Bertugas!',
                    'body' => 'Sistem telah aktif. Utamakan keselamatan berkendara dan pastikan paket sampai ke tangan pelanggan dengan aman.',
                    'created_at' => now()->subHours(1),
                ]);
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Pemantauan GPS Aktif',
                    'body' => 'Pastikan Anda menekan tombol "Simulasikan GPS" atau memperbarui lokasi secara berkala agar pelanggan dapat melacak pengiriman.',
                    'created_at' => now()->subMinutes(30),
                ]);
            } else {
                $orders = \App\Models\Order::where('user_id', $user->id)->get();
                if ($orders->count() > 0) {
                    foreach ($orders as $order) {
                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'Pembayaran Berhasil',
                            'body' => "Pembayaran untuk order #{$order->order_id} berhasil diterima. Paket Anda sedang diproses.",
                            'created_at' => $order->created_at,
                        ]);

                        if ($order->payment_status === 'PAID') {
                            Notification::create([
                                'user_id' => $user->id,
                                'title' => 'Kurir Ditugaskan',
                                'body' => "Kurir Driver-" . mt_rand(10, 99) . " sedang menjemput paket #{$order->order_id} Anda.",
                                'created_at' => $order->created_at->addMinutes(5),
                            ]);
                        }
                    }
                } else {
                    // Welcome notifications for new accounts with no orders
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Selamat Datang di TrackIT!',
                        'body' => 'Selamat bergabung! Mulailah membuat pengiriman baru dengan menekan tombol plus (+) di bawah.',
                        'created_at' => now()->subHours(2),
                    ]);
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Layanan Lacak GPS Aktif',
                        'body' => 'Sistem live GPS sekarang aktif secara real-time. Anda bisa memantau pergerakan kurir di peta.',
                        'created_at' => now()->subHour(),
                    ]);
                }
            }

            // Fetch again after dynamic seeding
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(['id', 'title', 'body', 'created_at', 'read_at']);

            $unreadCount = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
        }

        // Dynamically map action URLs based on order payment status
        $notifications->transform(function ($item) {
            $url = null;
            if (preg_match('/(ORD-[A-Z0-9]+)/i', $item->body, $matches) || preg_match('/(ORD-[A-Z0-9]+)/i', $item->title, $matches)) {
                $orderId = strtoupper($matches[1]);
                $order = \App\Models\Order::where('order_id', $orderId)->first();
                if ($order) {
                    if ($order->payment_status === 'UNPAID') {
                        $url = route('customer.payment', $orderId);
                    } else {
                        $url = route('track') . '?order_id=' . $orderId;
                    }
                }
            }
            $item->action_url = $url;
            return $item;
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $notification = Notification::where('id', $id)->where('user_id', $user->id)->first();
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->read_at = now();
        $notification->save();

        return response()->json(['status' => 'ok']);
    }
}
?>
