@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')

<div class="container mt-4">

    <h2>Driver Dashboard</h2>

    <p>
        Welcome, {{ auth()->user()->name }}
    </p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="alert alert-info">

        WebSocket Status:

        <strong id="connectionStatus">
            Connecting...
        </strong>

    </div>


    <h4 class="mt-4">My Assigned Rides</h4>

    <div class="row" id="ridesContainer">

        @forelse($rides as $ride)

            <div class="col-md-6 mb-3" id="ride-{{ $ride->id }}">

                <div class="card shadow">

                    <div class="card-body">

                        <h5>Ride #{{ $ride->id }}</h5>

                        <p>
                            <strong>Pickup:</strong>
                            {{ $ride->pickup_location }}
                        </p>

                        <p>
                            <strong>Drop:</strong>
                            {{ $ride->drop_location }}
                        </p>

                        <p>
                            <strong>Status:</strong>
                            <span class="badge bg-primary">
                                {{ ucfirst($ride->status) }}
                            </span>
                        </p>

                        @if($ride->status === 'assigned')

                            <form method="POST"
                                  action="{{ route('driver.ride.status', $ride->id) }}">

                                @csrf

                                <input type="hidden"
                                       name="status"
                                       value="accepted">

                                <button class="btn btn-success">
                                    Accept
                                </button>

                            </form>

                            <form method="POST"
                                  action="{{ route('driver.ride.status', $ride->id) }}"
                                  class="mt-2">

                                @csrf

                                <input type="hidden"
                                       name="status"
                                       value="rejected">

                                <button class="btn btn-danger">
                                    Reject
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <p id="noRidesMessage">
                No rides assigned yet.
            </p>

        @endforelse

    </div>

</div>


<!-- Real-time notification popup -->

<div id="ridePopup"
     style="
        display:none;
        position:fixed;
        top:20px;
        right:20px;
        width:350px;
        background:white;
        padding:20px;
        border-radius:12px;
        box-shadow:0 5px 25px rgba(0,0,0,0.2);
        border-left:5px solid green;
        z-index:99999;
     ">

    <h4 class="text-success">
        🚖 New Ride Assigned!
    </h4>

    <p id="popupMessage"></p>

    <p>
        <strong>Pickup:</strong>
        <span id="popupPickup"></span>
    </p>

    <p>
        <strong>Drop:</strong>
        <span id="popupDrop"></span>
    </p>

    <button onclick="closePopup()"
            class="btn btn-secondary">
        Close
    </button>

    <button onclick="viewRide()"
            class="btn btn-success">
        View Ride
    </button>

</div>


@endsection


@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        // Wait until Laravel Echo is initialized

        window.addEventListener('load', function () {

            if (typeof window.Echo === 'undefined') {

                console.error('Laravel Echo is not initialized!');

                document.getElementById('connectionStatus').innerText =
                    'Echo not initialized';

                return;
            }

            const driverId = @json(auth()->id());

            let currentRideId = null;

            console.log('Driver ID:', driverId);

            console.log('Connecting to WebSocket...');

            window.Echo.private(`driver.${driverId}`)

                .subscribed(() => {

                    console.log('Successfully subscribed to driver channel!');

                    document.getElementById('connectionStatus').innerText =
                        'Connected';

                })

                .error((error) => {

                    console.error('Channel error:', error);

                    document.getElementById('connectionStatus').innerText =
                        'Connection error';

                })

                .listen('.ride.assigned', (event) => {

                    console.log('New ride received:', event);

                    currentRideId = event.ride_id;

                    document.getElementById('popupMessage').innerText =
                        event.message;

                    document.getElementById('popupPickup').innerText =
                        event.pickup_location;

                    document.getElementById('popupDrop').innerText =
                        event.drop_location;

                    document.getElementById('ridePopup').style.display =
                        'block';

                    addRideToDashboard(event);

                });


            function addRideToDashboard(ride) {

                document.getElementById('noRidesMessage')?.remove();

                const container = document.getElementById('ridesContainer');

                const card = document.createElement('div');

                card.className = 'col-md-6 mb-3';

                card.id = 'ride-' + ride.ride_id;

                card.innerHTML = `
                    <div class="card shadow">
                        <div class="card-body">

                            <h5>Ride #${ride.ride_id}</h5>

                            <p>
                                <strong>Pickup:</strong>
                                ${escapeHtml(ride.pickup_location)}
                            </p>

                            <p>
                                <strong>Drop:</strong>
                                ${escapeHtml(ride.drop_location)}
                            </p>

                            <p>
                                <strong>Status:</strong>
                                Assigned
                            </p>

                        </div>
                    </div>
                `;

                container.prepend(card);

            }


            function escapeHtml(value) {

                const div = document.createElement('div');

                div.textContent = value ?? '';

                return div.innerHTML;

            }


            window.closePopup = function () {

                document.getElementById('ridePopup').style.display = 'none';

            };


            window.viewRide = function () {

                if (currentRideId) {

                    document.getElementById('ride-' + currentRideId)
                        ?.scrollIntoView({
                            behavior: 'smooth'
                        });

                    closePopup();

                }

            };

        });

    });

</script>

@endpush