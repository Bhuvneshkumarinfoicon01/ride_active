<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ride;
use App\Events\RideAssigned;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $drivers = User::where('role', 'driver')->get();

        $rides = Ride::with('driver')
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'drivers',
            'rides'
        ));
    }

    public function assignRide(Request $request)
    {
        $request->validate([

            'driver_id' => 'required|exists:users,id',

            'pickup_location' => 'required|string|max:255',

            'drop_location' => 'required|string|max:255',

        ]);

        $driver = User::where('id', $request->driver_id)
            ->where('role', 'driver')
            ->firstOrFail();

        // Create ride

        $ride = Ride::create([

            'driver_id' => $driver->id,

            'pickup_location' => $request->pickup_location,

            'drop_location' => $request->drop_location,

            'status' => 'assigned',

        ]);

        // Broadcast real-time event

        event(new RideAssigned($ride));
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Ride assigned successfully!');

    }
}