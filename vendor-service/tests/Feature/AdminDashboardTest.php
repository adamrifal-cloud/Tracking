<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin dashboard page renders with stats and shipments.
     */
    public function test_admin_dashboard_renders_with_mocked_apis(): void
    {
        // Mock external API calls from AdminDashboardController
        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers' => Http::response([
                ['id' => 2, 'name' => 'Asep Kurir']
            ], 200),
            'http://tracking-service-app:8000/api/v1/internal/allocations/count' => Http::response([
                'count' => 12
            ], 200),
            'http://tracking-service-app:8000/api/v1/internal/queue/status' => Http::response([
                'status' => 'Healthy'
            ], 200),
            'http://tracking-service-app:8000/api/v1/internal/admin/shipments' => Http::response([
                [
                    'order_id' => 'ORD-123',
                    'customer_name' => 'Budi Customer',
                    'receiver_name' => 'Siti Receiver',
                    'package_description' => 'Pakaian',
                    'package_weight' => 2.5,
                    'driver_id' => '2',
                    'driver_name' => 'Asep Kurir',
                    'status' => 'Perjalanan',
                    'last_update' => '2026-06-07 12:00:00'
                ]
            ], 200),
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('totalAllocations', 12);
        $response->assertViewHas('activeDrivers', 1);
        $response->assertViewHas('queueStatus', 'Healthy');
        $response->assertViewHas('shipments');
    }

    /**
     * Test admin vendors page renders.
     */
    public function test_admin_vendors_page_renders(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin/vendors');

        $response->assertStatus(200);
        $response->assertViewIs('admin.vendors');
        $response->assertViewHas('pendingVendors');
    }



    /**
     * Test admin can fetch unallocated orders via internal AJAX route.
     */
    public function test_admin_can_fetch_unallocated_orders(): void
    {
        // Mock external API call
        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/unallocated-orders' => Http::response([
                ['order_id' => 'ORD-NEW-777']
            ], 200),
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->getJson('/admin/unread-orders');

        $response->assertStatus(200);
        $response->assertJson([
            ['order_id' => 'ORD-NEW-777']
        ]);
    }

    /**
     * Test guest cannot fetch unallocated orders.
     */
    public function test_guest_cannot_fetch_unallocated_orders(): void
    {
        $response = $this->getJson('/admin/unread-orders');
        $response->assertStatus(401);
    }

    /**
     * Test admin can delete completed order.
     */
    public function test_admin_can_delete_completed_order(): void
    {
        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/orders/ORD-DELETE-111' => Http::response([
                'success' => true
            ], 200),
        ]);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->delete('/admin/orders/ORD-DELETE-111');

        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('success', 'Pengiriman #ORD-DELETE-111 berhasil dihapus.');
    }
}
