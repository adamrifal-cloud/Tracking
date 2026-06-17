<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ListenDriverAllocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_listen_driver_allocation_processes_message_and_creates_notification(): void
    {
        // Seed driver user to satisfy foreign key constraint on notifications table
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Asep Kurir',
            'email' => 'asep@example.com',
            'password' => bcrypt('password'),
            'role' => 'DRIVER',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mock the Job
        $mockJob = $this->createMock(\Illuminate\Contracts\Queue\Job::class);
        $mockJob->method('getRawBody')->willReturn(json_encode([
            'displayName' => json_encode([
                'order_id' => 'ORD-12345',
                'driver_id' => 2,
                'driver_name' => 'Asep Kurir',
            ])
        ]));
        $mockJob->method('delete')->willReturn(true);

        // Mock the Queue Connection with consecutive behavior using callback
        $callCount = 0;
        $mockConnection = $this->createMock(\Illuminate\Contracts\Queue\Queue::class);
        $mockConnection->method('pop')
            ->with('driver-allocations')
            ->willReturnCallback(function () use (&$callCount, $mockJob) {
                $callCount++;
                if ($callCount === 1) {
                    return $mockJob;
                }
                // Throw an Error (not Exception) to break the infinite loop in ListenDriverAllocation handle()
                throw new \Error("Exit Loop");
            });

        // Mock the Queue Manager
        $mockQueueManager = $this->createMock(\Illuminate\Queue\QueueManager::class);
        $mockQueueManager->method('connection')
            ->with('rabbitmq')
            ->willReturn($mockConnection);

        $this->app->instance('queue', $mockQueueManager);

        // Run the Artisan command
        try {
            $this->artisan('rabbitmq:listen-allocation');
            $this->fail('The command should have thrown an Error to exit the loop.');
        } catch (\Error $e) {
            $this->assertEquals("Exit Loop", $e->getMessage());
        }

        // Verify the database updates in tracking_db
        $this->assertDatabaseHas('trackings', [
            'order_id' => 'ORD-12345',
            'driver_id' => 'Driver-2',
            'status' => 'Menunggu Konfirmasi Driver',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => 2,
            'title' => 'Penugasan Pengiriman Baru',
            'body' => 'Anda telah ditugaskan untuk mengirim paket dengan Order ID: ORD-12345. Harap lakukan konfirmasi penugasan.',
        ]);
    }

    public function test_driver_can_accept_allocated_task(): void
    {
        // Seed driver user
        $driverId = DB::table('users')->insertGetId([
            'name' => 'Asep Kurir',
            'email' => 'asep@example.com',
            'password' => bcrypt('password'),
            'role' => 'DRIVER',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $driver = \App\Models\User::find($driverId);

        // Seed order
        DB::table('orders')->insert([
            'order_id' => 'ORD-99999',
            'user_id' => 1,
            'sender_name' => 'Sender',
            'sender_phone' => '123',
            'sender_address' => 'Addr A',
            'receiver_name' => 'Receiver',
            'receiver_phone' => '456',
            'receiver_address' => 'Addr B',
            'package_description' => 'Box',
            'package_weight' => 2.0,
            'price' => 20000,
            'payment_status' => 'PAID',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed tracking record in pending confirmation status
        DB::table('trackings')->insert([
            'order_id' => 'ORD-99999',
            'driver_id' => 'Driver-' . $driverId,
            'status' => 'Menunggu Konfirmasi Driver',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send accept request as authenticated driver
        $response = $this->actingAs($driver)
            ->postJson("/driver/task/ORD-99999/accept");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Tugas pengiriman berhasil diterima!'
            ]);

        // Verify status in DB changed to Driver Terpilih - Bersiap Meluncur
        $this->assertDatabaseHas('trackings', [
            'order_id' => 'ORD-99999',
            'status' => 'Driver Terpilih - Bersiap Meluncur',
        ]);

        // Verify customer notification was created
        $this->assertDatabaseHas('notifications', [
            'user_id' => 1,
            'title' => 'Kurir Ditugaskan',
            'body' => 'Kurir Asep Kurir telah mengonfirmasi pengiriman #ORD-99999 Anda dan sedang bersiap meluncur.',
        ]);
    }
}
