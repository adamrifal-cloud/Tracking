<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest redirects.
     */
    public function test_guest_is_redirected_from_vendor_pages(): void
    {
        $this->get('/vendor/notifications')->assertRedirect(route('vendor.login'));
    }

    /**
     * Test vendor user can access pages.
     */
    public function test_vendor_can_access_dashboard_and_notifications_pages(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);
        
        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'PT. Test Logistik',
            'company_address' => 'Jl. Uji Coba No. 123',
            'document_path' => 'test.pdf',
            'document_status' => 'Verified',
        ]);

        $response = $this->actingAs($vendor)->get('/vendor/notifications');
        $response->assertStatus(200);
        $response->assertViewIs('vendor.notifications.index');
    }
}
