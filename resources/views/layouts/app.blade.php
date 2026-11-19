<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RideAlert</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a class="navbar-brand" href="#">
                RideAlert
            </a>

            @auth

                <div class="text-white">

                    {{ auth()->user()->name }}

                    <form action="{{ route('logout') }}"
                          method="POST"
                          class="d-inline">

                        @csrf

                        <button class="btn btn-danger btn-sm ms-2">
                            Logout
                        </button>

                    </form>

                </div>

            @endauth

        </div>

    </nav>

    @yield('content')

    @vite(['resources/js/app.js'])

    @stack('scripts')

</body>

</html>