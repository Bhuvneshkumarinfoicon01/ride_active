<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function dashboard()
    {
        $rides = Ride::where('driver_id', auth()->id())
            ->latest()
            ->get();

        return view('driver.dashboard', compact('rides'));
    }

    public function updateRideStatus(Request $request, Ride $ride)
    {
        // Make sure the ride belongs to logged-in driver

        if ((int) $ride->driver_id !== (int) auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Only assigned rides can be accepted or rejected

        if ($ride->status !== 'assigned') {
            return back()->with('error', 'This ride is no longer available.');
        }

        $ride->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Ride ' . $request->status . ' successfully!'
        );
    }
}