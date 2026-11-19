<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Events\RideAssigned;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function assignRide(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'pickup_location' => 'required|string',
            'drop_location' => 'required|string',
        ]);

        $driver = User::where('id', $request->driver_id)
            ->where('role', 'driver')
            ->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid driver.',
            ], 422);
        }

        // Save ride in database
        $ride = Ride::create([
            'driver_id' => $driver->id,
            'pickup_location' => $request->pickup_location,
            'drop_location' => $request->drop_location,
            'status' => 'assigned',
        ]);

        // Broadcast event to assigned driver
        event(new RideAssigned($ride));

        return response()->json([
            'success' => true,
            'message' => 'Ride assigned successfully.',
            'data' => $ride,
        ]);
    }
}