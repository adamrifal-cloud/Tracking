<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;

class VendorDashboardController extends Controller
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
     * Redirect root request based on authentication status and user role.
     */
    public function rootRedirect()
    {
        if (Auth::check()) {
            if (Auth::user()->role === \App\Models\User::ROLE_ADMIN) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === \App\Models\User::ROLE_VENDOR) {
                return redirect()->route('vendor.dashboard');
            }
        }
        return redirect()->route('vendor.login');
    }

    /**
     * Show the Vendor Dashboard.
     */
    public function index()
    {
        $vendor = Auth::user();

        // Get document status from relation, default to 'Not Submitted' if doesn't exist
        $documentStatus = $vendor->vendorProfile ? $vendor->vendorProfile->document_status : 'Not Submitted';
        
        // Get counts for dashboard stats
        $vendorName = $vendor->vendorProfile->company_name ?? $vendor->name;
        try {
            $response = Http::timeout(5)->get($this->trackingServiceUrl . '/api/v1/internal/drivers/all', [
                'vendor_name' => $vendorName
            ]);
            $totalDrivers = $response->successful() ? count($response->json()) : 0;
        } catch (\Exception $e) {
            $totalDrivers = 0;
        }

        $unreadNotificationsCount = $vendor->notifications()->whereNull('read_at')->count();

        return view('vendor.dashboard', compact(
            'vendor',
            'documentStatus',
            'totalDrivers',
            'unreadNotificationsCount'
        ));
    }

    /**
     * Show the Vendor Notifications Page.
     */
    public function notificationsPage()
    {
        $vendor = Auth::user();

        // Get all notifications for the vendor
        $notifications = $vendor->notifications()->latest()->get();

        return view('vendor.notifications.index', compact(
            'vendor',
            'notifications'
        ));
    }

    /**
     * Mark a notification as read.
     */
    public function readNotification(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    /**
     * Show vendor's drivers page.
     */
    public function driversPage()
    {
        $vendorName = auth()->user()->vendorProfile->company_name ?? auth()->user()->name;
        try {
            $response = Http::timeout(15)->get($this->trackingServiceUrl . '/api/v1/internal/drivers/all', [
                'vendor_name' => $vendorName
            ]);
            $drivers = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $drivers = [];
        }

        try {
            $applicantsResponse = Http::timeout(15)->get($this->trackingServiceUrl . '/api/v1/internal/drivers/all', [
                'unaffiliated' => 'true'
            ]);
            $applicants = $applicantsResponse->successful() ? $applicantsResponse->json() : [];
        } catch (\Exception $e) {
            $applicants = [];
        }

        return view('vendor.drivers', compact('drivers', 'applicants'));
    }

    /**
     * Store a new driver under this vendor.
     */
    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Automatically assign the driver to this vendor
        $vendorName = auth()->user()->vendorProfile->company_name ?? auth()->user()->name;
        $validated['vendor_name'] = $vendorName;

        try {
            $response = Http::timeout(10)
                ->post($this->trackingServiceUrl . '/api/v1/internal/drivers', $validated);

            if ($response->successful()) {
                return redirect()->route('vendor.drivers.index')->with('success', 'Driver berhasil ditambahkan.');
            }

            $error = $response->json()['message'] ?? 'Gagal menghubungi server database tracking.';
            return redirect()->back()->withInput()->withErrors(['api_error' => $error]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['api_error' => 'Gagal menghubungi server: ' . $e->getMessage()]);
        }
    }

    /**
     * Update an existing driver's status.
     */
    public function updateDriver(Request $request, $id)
    {
        $rules = [
            'status' => 'required|in:active,inactive,suspended',
        ];

        // If we are recruiting an unaffiliated driver
        if ($request->has('recruit') && $request->input('recruit') == 'true') {
            $vendorName = auth()->user()->vendorProfile->company_name ?? auth()->user()->name;
            $request->merge([
                'vendor_name' => $vendorName,
                'status' => 'active'
            ]);
            $rules['vendor_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);
        
        // Keep only validated fields in data
        $data = [
            'status' => $validated['status']
        ];
        if (isset($validated['vendor_name'])) {
            $data['vendor_name'] = $validated['vendor_name'];
        }

        try {
            $response = Http::timeout(10)
                ->put($this->trackingServiceUrl . '/api/v1/internal/drivers/' . $id, $data);

            if ($response->successful()) {
                $msg = isset($data['vendor_name']) ? 'Driver berhasil direkrut dan diaktifkan.' : 'Driver berhasil diperbarui.';
                return redirect()->route('vendor.drivers.index')->with('success', $msg);
            }

            $error = $response->json()['message'] ?? 'Gagal menghubungi server database tracking.';
            return redirect()->back()->withInput()->withErrors(['api_error' => $error]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['api_error' => 'Gagal menghubungi server: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a driver.
     */
    public function destroyDriver($id)
    {
        try {
            $response = Http::timeout(10)
                ->delete($this->trackingServiceUrl . '/api/v1/internal/drivers/' . $id);

            if ($response->successful()) {
                return redirect()->route('vendor.drivers.index')->with('success', 'Driver berhasil dihapus.');
            }

            return redirect()->route('vendor.drivers.index')->withErrors(['api_error' => 'Gagal menghapus driver.']);
        } catch (\Exception $e) {
            return redirect()->route('vendor.drivers.index')->withErrors(['api_error' => 'Gagal menghubungi server: ' . $e->getMessage()]);
        }
    }
}
