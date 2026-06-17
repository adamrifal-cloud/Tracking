<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    /**
     * The Tracking Service Base URL.
     *
     * @var string
     */
    protected $trackingServiceUrl;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->trackingServiceUrl = config('services.tracking.url', 'http://tracking-service-app:8000');
    }

    /**
     * Show the Admin Dashboard.
     */
    public function index()
    {
        // Fetch drivers from tracking-service API to get count and list
        try {
            $driversResponse = Http::timeout(15)->get($this->trackingServiceUrl . '/api/v1/internal/drivers');
            $drivers = $driversResponse->successful() ? $driversResponse->json() : [];
            $activeDrivers = count($drivers);
        } catch (\Exception $e) {
            $drivers = [];
            $activeDrivers = 0;
        }

        // Dynamically fetch total allocations count from API
        try {
            $allocationsResponse = Http::timeout(5)->get($this->trackingServiceUrl . '/api/v1/internal/allocations/count');
            $totalAllocations = $allocationsResponse->successful() ? ($allocationsResponse->json()['count'] ?? 0) : 0;
        } catch (\Exception $e) {
            $totalAllocations = 0;
        }

        // Dynamically check queue status from API
        try {
            $queueResponse = Http::timeout(5)->get($this->trackingServiceUrl . '/api/v1/internal/queue/status');
            $queueStatus = $queueResponse->successful() ? ($queueResponse->json()['status'] ?? 'Offline') : 'Offline';
        } catch (\Exception $e) {
            $queueStatus = 'Offline';
        }

        // Fetch shipments list from tracking-service API
        try {
            $shipmentsResponse = Http::timeout(15)->get($this->trackingServiceUrl . '/api/v1/internal/admin/shipments');
            $shipments = $shipmentsResponse->successful() ? $shipmentsResponse->json() : [];
        } catch (\Exception $e) {
            $shipments = [];
        }

        // Fetch verified vendors list
        $verifiedVendors = User::where('role', User::ROLE_VENDOR)
            ->whereHas('vendorProfile', function ($query) {
                $query->where('document_status', 'Verified');
            })
            ->with('vendorProfile')
            ->get();

        return view('admin.dashboard', compact(
            'totalAllocations', 
            'activeDrivers', 
            'queueStatus',
            'shipments',
            'drivers',
            'verifiedVendors'
        ));
    }

    /**
     * Show pending vendors validation page.
     */
    public function vendorsPage()
    {
        // Get list of pending vendors who have submitted documents and are waiting for verification
        $pendingVendors = User::where('role', User::ROLE_VENDOR)
            ->whereHas('vendorProfile', function ($query) {
                $query->where('document_status', 'Pending Verification');
            })
            ->with('vendorProfile')
            ->get();

        return view('admin.vendors', compact('pendingVendors'));
    }



    /**
     * Allocate a driver to an order and dispatch RabbitMQ job.
     */
    public function allocateDriver(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'driver_id' => 'required|integer',
            'driver_name' => 'required|string',
            'vendor_name' => 'required|string',
        ]);

        // Find the Vendor user by company name or user name
        $vendorUser = User::where('role', User::ROLE_VENDOR)
            ->where(function ($query) use ($validated) {
                $query->where('name', $validated['vendor_name'])
                      ->orWhereHas('vendorProfile', function ($q) use ($validated) {
                          $q->where('company_name', $validated['vendor_name']);
                      });
            })->first();

        if ($vendorUser) {
            \App\Models\Notification::create([
                'user_id' => $vendorUser->id,
                'order_id' => $validated['order_id'],
                'message' => 'Tugas pengiriman baru ditugaskan ke driver Anda! Harap pantau penjemputan barang untuk Order ID: ' . $validated['order_id'],
            ]);
        }

        dispatch(new \App\Jobs\SendDriverAllocation($validated))->onQueue('driver-allocations');

        return response()->json(['message' => 'Tugas ' . $validated['order_id'] . ' berhasil dikirim ke antrean kurir.']);
    }

    /**
     * Verify the vendor document status.
     */
    public function verifyVendor(Request $request, User $vendor)
    {
        $validated = $request->validate([
            'status' => 'required|in:Verified,Rejected',
        ]);

        $profile = $vendor->vendorProfile;
        if ($profile) {
            $profile->document_status = $validated['status'];
            $profile->save();
        }

        $message = $validated['status'] === 'Verified'
            ? "Vendor {$vendor->name} telah berhasil diverifikasi."
            : "Dokumen vendor {$vendor->name} telah ditolak.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get unallocated orders for AJAX notification polling.
     */
    public function getUnallocatedOrders()
    {
        try {
            $ordersResponse = Http::timeout(10)->get($this->trackingServiceUrl . '/api/v1/internal/unallocated-orders');
            $unallocatedOrders = $ordersResponse->successful() ? $ordersResponse->json() : [];
        } catch (\Exception $e) {
            $unallocatedOrders = [];
        }

        return response()->json($unallocatedOrders);
    }



    /**
     * Delete an order/shipment and its tracking records (Admin Action).
     */
    public function destroyOrder($order_id)
    {
        try {
            $response = Http::timeout(10)
                ->delete($this->trackingServiceUrl . '/api/v1/internal/orders/' . $order_id);

            if ($response->successful()) {
                return redirect()->route('admin.dashboard')->with('success', 'Pengiriman #' . $order_id . ' berhasil dihapus.');
            }

            return redirect()->route('admin.dashboard')->withErrors(['api_error' => 'Gagal menghapus pengiriman.']);
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->withErrors(['api_error' => 'Gagal menghubungi server: ' . $e->getMessage()]);
        }
    }
}
