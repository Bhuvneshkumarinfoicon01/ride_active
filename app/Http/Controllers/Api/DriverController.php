<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // Get all rides assigned to logged-in driver
    public function rides(Request $request)
    {
        $rides = Ride::where('driver_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Driver rides fetched successfully.',
            'data' => $rides
        ]);
    }

    // Get single ride details
    public function rideDetails($id)
    {
        $ride = Ride::where('id', $id)
            ->where('driver_id', auth()->id())
            ->first();

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Ride not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ride
        ]);
    }

    // Accept or reject ride
    public function updateRideStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $ride = Ride::where('id', $id)
            ->where('driver_id', auth()->id())
            ->first();

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Ride not found.'
            ], 404);
        }

        if ($ride->status !== 'assigned') {
            return response()->json([
                'success' => false,
                'message' => 'This ride can no longer be updated.'
            ], 422);
        }

        $ride->status = $request->status;
        $ride->save();

        return response()->json([
            'success' => true,
            'message' => 'Ride status updated successfully.',
            'data' => $ride
        ]);
    }
}