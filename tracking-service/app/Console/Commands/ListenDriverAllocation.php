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
    
    while (true) {
        try {
            // Ambil data dari antrean 'driver-allocations'
            $job = $connection->pop('driver-allocations');

            if ($job) {
                // Decode data payload dari RabbitMQ
                $payload = json_decode($job->getRawBody(), true);
                $data = json_decode($payload['displayName'], true);

                $this->warn('--- PESAN DITERIMA ---');
                $this->line('Order ID: ' . $data['order_id']);
                $this->line('Driver : ' . $data['driver_name']);

                // Simpan/Update data ke DB tracking_db secara otomatis
                \DB::table('trackings')->updateOrInsert(
                    ['order_id' => $data['order_id']],
                    [
                        'driver_id' => $data['driver_id'],
                        'status' => 'Driver Terpilih - Bersiap Meluncur',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                $this->info('Sukses memperbarui database tracking!');
                
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
        }
    }
}
}