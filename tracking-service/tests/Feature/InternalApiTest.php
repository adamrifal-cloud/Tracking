<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InternalApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test internal drivers API.
     */
    public function test_internal_drivers_api_returns_active_drivers(): void
    {
        // Create an active driver
        User::factory()->create([
            'name' => 'Driver Active',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Create an inactive driver
        User::factory()->create([
            'name' => 'Driver Inactive',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_INACTIVE,
        ]);

        // Create a customer
        User::factory()->create([
            'name' => 'Customer User',
            'role' => User::ROLE_CUSTOMER,
        ]);

        $response = $this->getJson('/api/v1/internal/drivers');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Driver Active']);
        $response->assertJsonMissing(['name' => 'Driver Inactive']);
        $response->assertJsonMissing(['name' => 'Customer User']);
    }

    /**
     * Test internal unallocated orders API.
     */
    public function test_internal_unallocated_orders_api_returns_orders_without_drivers(): void
    {
        // Insert unallocated order
        DB::table('trackings')->insert([
            'order_id' => 'ORD-UNALLOCATED',
            'driver_id' => null,
            'status' => 'Dikemas',
            'terakhir_diupdate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert allocated order
        DB::table('trackings')->insert([
            'order_id' => 'ORD-ALLOCATED',
            'driver_id' => 'Driver-1',
            'status' => 'Perjalanan',
            'terakhir_diupdate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/internal/unallocated-orders');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['order_id' => 'ORD-UNALLOCATED']);
        $response->assertJsonMissing(['order_id' => 'ORD-ALLOCATED']);
    }

    /**
     * Test internal shipments API.
     */
    public function test_internal_shipments_api_returns_joined_shipment_details(): void
    {
        $customer = User::factory()->create([
            'name' => 'Budi Customer',
            'role' => User::ROLE_CUSTOMER,
        ]);

        $driver = User::factory()->create([
            'name' => 'Asep Kurir',
            'role' => User::ROLE_DRIVER,
        ]);

        DB::table('orders')->insert([
            'order_id' => 'ORD-123',
            'user_id' => $customer->id,
            'sender_name' => 'Budi Customer',
            'sender_phone' => '08123456789',
            'sender_address' => 'Jl. Pengirim',
            'receiver_name' => 'Siti Receiver',
            'receiver_phone' => '08987654321',
            'receiver_address' => 'Jl. Penerima',
            'package_description' => 'Pakaian',
            'package_weight' => 2.5,
            'price' => 15000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('trackings')->insert([
            'order_id' => 'ORD-123',
            'driver_id' => 'Driver-' . $driver->id,
            'status' => 'Perjalanan',
            'terakhir_diupdate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/internal/admin/shipments');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'order_id' => 'ORD-123',
            'customer_name' => 'Budi Customer',
            'receiver_name' => 'Siti Receiver',
            'package_description' => 'Pakaian',
            'driver_name' => 'Asep Kurir',
            'status' => 'Perjalanan',
        ]);
    }

    /**
     * Test fetching all drivers (including inactive/suspended ones).
     */
    public function test_internal_drivers_api_returns_all_drivers(): void
    {
        User::factory()->create([
            'name' => 'Driver Active',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
        ]);

        User::factory()->create([
            'name' => 'Driver Suspended',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_SUSPENDED,
        ]);

        $response = $this->getJson('/api/v1/internal/drivers/all');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * Test filtering drivers by vendor name.
     */
    public function test_internal_drivers_api_filters_by_vendor_name(): void
    {
        User::factory()->create([
            'name' => 'Driver Asemka',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
            'vendor_name' => 'Asemka Logistik',
        ]);

        User::factory()->create([
            'name' => 'Driver Express',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
            'vendor_name' => 'Express Delivery',
        ]);

        // Test active drivers endpoint with filter
        $response = $this->getJson('/api/v1/internal/drivers?vendor_name=Asemka+Logistik');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Driver Asemka']);
        $response->assertJsonMissing(['name' => 'Driver Express']);

        // Test all drivers endpoint with filter
        $response = $this->getJson('/api/v1/internal/drivers/all?vendor_name=Express+Delivery');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Driver Express']);
        $response->assertJsonMissing(['name' => 'Driver Asemka']);
    }

    /**
     * Test creating a driver via internal API.
     */
    public function test_internal_drivers_api_creates_driver(): void
    {
        $response = $this->postJson('/api/v1/internal/drivers', [
            'name' => 'Pak Supir Baru',
            'email' => 'supirbaru@example.com',
            'phone' => '08111222333',
            'password' => 'securesupir123',
            'vehicle_number' => 'B 9999 XYZ',
            'license_number' => 'SIM-111222333',
            'status' => 'active',
            'vendor_name' => 'Asemka Logistik',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Pak Supir Baru',
            'email' => 'supirbaru@example.com',
            'status' => 'active',
            'vendor_name' => 'Asemka Logistik',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'supirbaru@example.com',
            'role' => User::ROLE_DRIVER,
            'vendor_name' => 'Asemka Logistik',
        ]);
    }

    /**
     * Test updating a driver via internal API.
     */
    public function test_internal_drivers_api_updates_driver(): void
    {
        $driver = User::factory()->create([
            'name' => 'Supir Lama',
            'email' => 'supirlama@example.com',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this->putJson('/api/v1/internal/drivers/' . $driver->id, [
            'name' => 'Supir Diupdate',
            'email' => 'supirlama@example.com',
            'phone' => '08222333444',
            'vehicle_number' => 'B 8888 XYZ',
            'license_number' => 'SIM-222333444',
            'status' => 'suspended',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Supir Diupdate',
            'status' => 'suspended',
        ]);
    }

    /**
     * Test deleting a driver via internal API.
     */
    public function test_internal_drivers_api_deletes_driver(): void
    {
        $driver = User::factory()->create([
            'name' => 'Supir Dihapus',
            'role' => User::ROLE_DRIVER,
        ]);

        $response = $this->deleteJson('/api/v1/internal/drivers/' . $driver->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => $driver->id,
        ]);
    }

    /**
     * Test busy drivers are excluded from the drivers list.
     */
    public function test_internal_drivers_api_excludes_busy_drivers(): void
    {
        // Active driver 1 (idle)
        $driver1 = User::factory()->create([
            'name' => 'Driver Idle',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Active driver 2 (busy delivering)
        $driver2 = User::factory()->create([
            'name' => 'Driver Busy',
            'role' => User::ROLE_DRIVER,
            'status' => User::STATUS_ACTIVE,
        ]);

        DB::table('trackings')->insert([
            'order_id' => 'ORD-BUSY-99',
            'driver_id' => 'Driver-' . $driver2->id,
            'status' => 'Perjalanan',
            'terakhir_diupdate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/internal/drivers');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Driver Idle']);
        $response->assertJsonMissing(['name' => 'Driver Busy']);
    }

    public function test_internal_orders_api_deletes_order(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
        ]);

        DB::table('orders')->insert([
            'order_id' => 'ORD-TO-DELETE',
            'user_id' => $customer->id,
            'sender_name' => 'Pengirim',
            'sender_phone' => '123',
            'sender_address' => 'Alamat',
            'receiver_name' => 'Penerima',
            'receiver_phone' => '456',
            'receiver_address' => 'Alamat',
            'package_description' => 'Barang',
            'package_weight' => 1.0,
            'price' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('trackings')->insert([
            'order_id' => 'ORD-TO-DELETE',
            'driver_id' => 'Driver-1',
            'status' => 'Selesai',
            'terakhir_diupdate' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->deleteJson('/api/v1/internal/orders/ORD-TO-DELETE');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('orders', ['order_id' => 'ORD-TO-DELETE']);
        $this->assertDatabaseMissing('trackings', ['order_id' => 'ORD-TO-DELETE']);
    }
}
