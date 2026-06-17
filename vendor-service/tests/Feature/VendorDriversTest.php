<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VendorDriversTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test vendor can access drivers page with mocked internal API.
     */
    public function test_vendor_can_access_drivers_page(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);

        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'Asemka Logistik',
            'document_status' => 'Verified'
        ]);

        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers/all?unaffiliated=true' => Http::response([
                [
                    'id' => 12,
                    'name' => 'Pelamar Baru',
                    'email' => 'pelamar@example.com',
                    'phone' => '08123123123',
                    'vehicle_number' => null,
                    'license_number' => null,
                    'status' => 'inactive',
                    'vendor_name' => null
                ]
            ], 200),
            'http://tracking-service-app:8000/api/v1/internal/drivers/all*' => Http::response([
                [
                    'id' => 10,
                    'name' => 'Budi Kurir',
                    'email' => 'budikurir@example.com',
                    'phone' => '08123456789',
                    'vehicle_number' => 'B 4321 CD',
                    'license_number' => 'SIM-123456',
                    'status' => 'active',
                    'vendor_name' => 'Asemka Logistik'
                ]
            ], 200),
        ]);

        $response = $this->actingAs($vendor)->get('/vendor/drivers');

        $response->assertStatus(200);
        $response->assertViewIs('vendor.drivers');
        $response->assertViewHas('drivers');
        $response->assertViewHas('applicants');
        $response->assertSee('Budi Kurir');
        $response->assertSee('Pelamar Baru');
    }

    /**
     * Test guest is redirected from drivers page.
     */
    public function test_guest_is_redirected_from_drivers_page(): void
    {
        $response = $this->get('/vendor/drivers');
        $response->assertRedirect('/vendor/login');
    }

    /**
     * Test vendor can store a new driver.
     */
    public function test_vendor_can_store_driver(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);

        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'Asemka Logistik',
            'document_status' => 'Verified'
        ]);

        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers' => Http::response([
                'id' => 11,
                'name' => 'Asep Driver Baru',
                'email' => 'asepdriver@example.com',
                'vendor_name' => 'Asemka Logistik'
            ], 201),
        ]);

        $response = $this->actingAs($vendor)->post('/vendor/drivers', [
            'name' => 'Asep Driver Baru',
            'email' => 'asepdriver@example.com',
            'phone' => '0899888777',
            'password' => 'asep123456',
            'vehicle_number' => 'B 9876 BC',
            'license_number' => 'SIM-887766',
            'status' => 'active'
        ]);

        $response->assertRedirect('/vendor/drivers');
        $response->assertSessionHas('success', 'Driver berhasil ditambahkan.');
    }

    /**
     * Test vendor can update an existing driver.
     */
    public function test_vendor_can_update_driver(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);

        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'Asemka Logistik',
            'document_status' => 'Verified'
        ]);

        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers/10' => Http::response([
                'id' => 10,
                'name' => 'Budi Diupdate',
                'email' => 'budikurir@example.com',
                'vendor_name' => 'Asemka Logistik'
            ], 200),
        ]);

        $response = $this->actingAs($vendor)->put('/vendor/drivers/10', [
            'status' => 'suspended'
        ]);

        $response->assertRedirect('/vendor/drivers');
        $response->assertSessionHas('success', 'Driver berhasil diperbarui.');
    }

    /**
     * Test vendor can recruit an applicant driver.
     */
    public function test_vendor_can_recruit_applicant(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);

        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'Asemka Logistik',
            'document_status' => 'Verified'
        ]);

        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers/12' => Http::response([
                'id' => 12,
                'name' => 'Pelamar Direkrut',
                'email' => 'pelamar@example.com',
                'vendor_name' => 'Asemka Logistik',
                'status' => 'active'
            ], 200),
        ]);

        $response = $this->actingAs($vendor)->put('/vendor/drivers/12', [
            'recruit' => 'true',
            'license_number' => 'SIM-9988',
            'vehicle_number' => 'B 777 ZZZ',
            'status' => 'active'
        ]);

        $response->assertRedirect('/vendor/drivers');
        $response->assertSessionHas('success', 'Driver berhasil direkrut dan diaktifkan.');
    }

    /**
     * Test vendor can delete a driver.
     */
    public function test_vendor_can_delete_driver(): void
    {
        $vendor = User::factory()->create([
            'role' => User::ROLE_VENDOR,
        ]);

        VendorProfile::create([
            'user_id' => $vendor->id,
            'company_name' => 'Asemka Logistik',
            'document_status' => 'Verified'
        ]);

        Http::fake([
            'http://tracking-service-app:8000/api/v1/internal/drivers/10' => Http::response([
                'success' => true
            ], 200),
        ]);

        $response = $this->actingAs($vendor)->delete('/vendor/drivers/10');

        $response->assertRedirect('/vendor/drivers');
        $response->assertSessionHas('success', 'Driver berhasil dihapus.');
    }
}
