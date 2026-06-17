<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListenDriverAllocation extends Command
{
    // Nama perintah yang akan dijalankan di terminal
    protected $signature = 'rabbitmq:listen-allocation';
    protected $description = 'Mendengarkan antrean alokasi driver dari Vendor Service';

    public function handle()
{
    $this->info('Mulai mendengarkan antrean [driver-allocations]...');

    $connection = app('queue')->connection('rabbitmq');
    $queueDeclared = false;
    
    while (true) {
        try {
            // Pastikan antrean dideklarasikan terlebih dahulu sebelum pop untuk menghindari exception "not_found" di RabbitMQ
            if (!$queueDeclared && method_exists($connection, 'declareQueue')) {
                $connection->declareQueue('driver-allocations');
                $queueDeclared = true;
            }

            // Ambil data dari antrean 'driver-allocations'
            $job = $connection->pop('driver-allocations');

            if ($job) {
                // Decode data payload dari RabbitMQ
                $payload = json_decode($job->getRawBody(), true);
                $data = json_decode($payload['displayName'], true);

                $this->warn('--- PESAN DITERIMA ---');
                $this->line('Order ID: ' . $data['order_id']);
                $this->line('Driver : ' . $data['driver_name']);

                $driverId = $data['driver_id'];
                if (is_numeric($driverId)) {
                    $driverId = 'Driver-' . $driverId;
                }

                // Simpan/Update data ke DB tracking_db secara otomatis menggunakan Eloquent
                $tracking = \App\Models\Tracking::updateOrCreate(
                    ['order_id' => $data['order_id']],
                    [
                        'driver_id' => $driverId,
                        'status' => 'Menunggu Konfirmasi Driver',
                        'terakhir_diupdate' => now(),
                    ]
                );

                // Simpan notifikasi baru untuk driver terpilih menggunakan Eloquent
                \App\Models\Notification::create([
                    'user_id' => intval($data['driver_id']),
                    'title' => 'Penugasan Pengiriman Baru',
                    'body' => "Anda telah ditugaskan untuk mengirim paket dengan Order ID: " . $data['order_id'] . ". Harap lakukan konfirmasi penugasan.",
                    'read_at' => null,
                ]);

                // ETA for packaging / waiting confirmation
                $eta = "Besok pukul 10:00 - 12:30";

                // Kirim event WebSocket (OrderLocationUpdated) secara real-time
                event(new \App\Events\OrderLocationUpdated(
                    $tracking->order_id,
                    $data['driver_name'],
                    $tracking->status,
                    $tracking->latitude ?? -6.200000,
                    $tracking->longitude ?? 106.816666,
                    0, // Stage 0: Dikemas / Menunggu Konfirmasi
                    $eta,
                    $tracking->updated_at->toIso8601String()
                ));

                $this->info('Sukses memperbarui database tracking, mengirim notifikasi, dan menyiarkan event WebSocket ke pelanggan!');
                
                // Hapus pesan dari RabbitMQ agar tidak diproses ulang
                $job->delete();
            } else {
                // JIKA ANTREAN KOSONG: Beri jeda 3 detik sebelum mengecek ulang!
                // Ini kunci agar RabbitMQ tidak kebanjiran request (flooding)
                sleep(3);
            }
        } catch (\Exception $e) {
            $this->error('Koneksi terputus, mencoba menghubungkan kembali dalam 5 detik...');
            sleep(5);
            
            // Re-instantiate koneksi jika putus akibat channel error sebelumnya
            $connection = app('queue')->connection('rabbitmq');
            $queueDeclared = false;
        }
    }
}
}