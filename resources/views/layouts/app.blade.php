<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1.mb-4 {
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .description {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.5;
        }
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons .btn {
            margin-right: 10px;
        }
        .btn.active {
            background-color: #003087;
            border-color: #003087;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <a href="{{ url('reservations/employees/availability/interval') }}" class="btn btn-primary {{ request()->is('reservations/employees/availability/interval') ? 'active' : '' }}">Check by Interval</a>
            <a href="{{ url('reservations/employees/availability/check') }}" class="btn btn-primary {{ request()->is('reservations/employees/availability/check') ? 'active' : '' }}">Check by Date & Time</a>
        </div>
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @yield('scripts')
</body>
</html>
