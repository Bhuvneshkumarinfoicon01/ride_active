@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container mt-4">

    <h2>Admin Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mt-4">

        <div class="card-header">
            <h4>Assign New Ride</h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.assignRide') }}">

                @csrf

                <div class="mb-3">

                    <label>Select Driver</label>

                    <select name="driver_id"
                            class="form-control"
                            required>

                        <option value="">Select Driver</option>

                        @foreach($drivers as $driver)

                            <option value="{{ $driver->id }}">
                                {{ $driver->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label>Pickup Location</label>

                    <input type="text"
                           name="pickup_location"
                           class="form-control"
                           placeholder="Delhi Airport"
                           required>

                </div>

                <div class="mb-3">

                    <label>Drop Location</label>

                    <input type="text"
                           name="drop_location"
                           class="form-control"
                           placeholder="Connaught Place"
                           required>

                </div>

                <button class="btn btn-primary">
                    Assign Ride
                </button>

            </form>

        </div>

    </div>


    <div class="card shadow mt-4">

        <div class="card-header">
            <h4>All Rides</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Driver</th>
                        <th>Pickup</th>
                        <th>Drop</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($rides as $ride)

                        <tr>

                            <td>{{ $ride->id }}</td>

                            <td>{{ $ride->driver?->name ?? 'Unassigned' }}</td>

                            <td>{{ $ride->pickup_location }}</td>

                            <td>{{ $ride->drop_location }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ ucfirst($ride->status) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center">
                                No rides found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection