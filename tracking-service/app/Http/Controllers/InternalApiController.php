<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InternalApiController extends Controller
{
    /**
     * Get active drivers who are not currently on duty.
     */
    public function drivers(Request $request)
    {
        // Get driver user IDs that are currently delivering
        $busyDriverIds = Tracking::whereNotNull('driver_id')
            ->where('status', 'NOT LIKE', '%diterima%')
            ->where('status', 'NOT LIKE', '%selesai%')
            ->where('status', 'NOT LIKE', '%delivered%')
            ->pluck('driver_id')
            ->map(function ($driverId) {
                return (int) str_replace('Driver-', '', $driverId);
            })
            ->toArray();

        $query = User::where('role', User::ROLE_DRIVER)
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotIn('id', $busyDriverIds);

        if ($request->has('vendor_name')) {
            $query->where('vendor_name', $request->query('vendor_name'));
        }

        $drivers = $query->get(['id', 'name', 'vendor_name']);
            
        return response()->json($drivers);
    }

    /**
     * Get orders without assigned drivers.
     */
    public function unallocatedOrders()
    {
        $orders = Tracking::whereNull('driver_id')
            ->get(['order_id']);
            
        return response()->json($orders);
    }

    /**
     * Count total allocations.
     */
    public function allocationsCount()
    {
        $count = Tracking::count();
        return response()->json(['count' => $count]);
    }

    /**
     * Check RabbitMQ queue status.
     */
    public function queueStatus()
    {
        $connection = @fsockopen('rabbitmq', 5672, $errno, $errstr, 2);
        if (is_resource($connection)) {
            fclose($connection);
            return response()->json(['status' => 'Healthy']);
        }
        return response()->json(['status' => 'Unreachable']);
    }

    /**
     * Get joined shipment details.
     */
    public function adminShipments()
    {
        $trackings = Tracking::with('order')->get();
        
        $shipments = $trackings->map(function ($tracking) {
            $driverName = 'Belum Ditunjuk';
            if ($tracking->driver_id) {
                $numericId = preg_replace('/[^0-9]/', '', $tracking->driver_id);
                if ($numericId) {
                    $driver = User::where('id', $numericId)->where('role', User::ROLE_DRIVER)->first();
                    if ($driver) {
                        $driverName = $driver->name;
                    }
                }
            }
            
            $customerName = 'Umum / Guest';
            $order = $tracking->order;
            if ($order) {
                $customerName = $order->sender_name;
                $customerUser = User::find($order->user_id);
                if ($customerUser) {
                    $customerName = $customerUser->name;
                }
            }
            
            return [
                'order_id' => $tracking->order_id,
                'customer_name' => $customerName,
                'sender_name' => $order ? $order->sender_name : '-',
                'receiver_name' => $order ? $order->receiver_name : '-',
                'package_description' => $order ? $order->package_description : '-',
                'package_weight' => $order ? $order->package_weight : '-',
                'driver_id' => $tracking->driver_id,
                'driver_name' => $driverName,
                'status' => $tracking->status,
                'last_update' => $tracking->terakhir_diupdate ?? $tracking->updated_at ?? '-',
            ];
        });
        
        return response()->json($shipments);
    }

    /**
     * Get all drivers (both active and inactive).
     */
    public function allDrivers(Request $request)
    {
        $query = User::where('role', User::ROLE_DRIVER);

        if ($request->has('unaffiliated') && $request->query('unaffiliated') === 'true') {
            $query->where(function ($q) {
                $q->whereNull('vendor_name')
                  ->orWhere('vendor_name', '');
            })->where('status', User::STATUS_INACTIVE);
        } elseif ($request->has('vendor_name')) {
            $query->where('vendor_name', $request->query('vendor_name'));
        }

        $drivers = $query->get();
        return response()->json($drivers);
    }



    /**
     * Update an existing driver.
     */
    public function updateDriver(Request $request, $id)
    {
        $driver = User::where('role', User::ROLE_DRIVER)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'status' => 'sometimes|required|in:active,inactive,suspended',
            'vendor_name' => 'nullable|string|max:255',
        ]);

        $data = [];
        foreach (['name', 'email', 'phone', 'vehicle_number', 'license_number', 'status', 'vendor_name'] as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        if ($request->has('password') && !empty($request->input('password'))) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $driver->update($data);

        return response()->json($driver);
    }

    /**
     * Delete a driver.
     */
    public function destroyDriver($id)
    {
        $driver = User::where('role', User::ROLE_DRIVER)->findOrFail($id);
        $driver->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete an order and its tracking records (Admin Action).
     */
    public function destroyOrder($order_id)
    {
        // Delete tracking
        Tracking::where('order_id', $order_id)->delete();
        
        // Delete order
        Order::where('order_id', $order_id)->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created driver.
     */
    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'status' => 'sometimes|required|in:active,inactive,suspended',
            'vendor_name' => 'nullable|string|max:255',
        ]);

        $driver = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_DRIVER,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'status' => $validated['status'] ?? User::STATUS_ACTIVE,
            'vendor_name' => $validated['vendor_name'] ?? null,
        ]);

        return response()->json($driver, 201);
    }
}
