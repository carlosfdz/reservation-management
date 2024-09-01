<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
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
        .btn-success {
            background-color: #126225;
            border-color: #126225;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <a href="{{ url('reservations/employees/availability/interval') }}" class="btn btn-primary {{ request()->is('reservations/employees/availability/interval') ? 'active' : '' }}">Check by Interval</a>
            <a href="{{ url('reservations/employees/availability/check') }}" class="btn btn-primary {{ request()->is('reservations/employees/availability/check') ? 'active' : '' }}">Check by Date & Time</a>
            <a href="{{ url('reservations/employees/export-schedule') }}" class="btn btn-success" id="download-schedule-btn">Download Schedule</a>
            <a href="{{ route('reservations.employees.schedule') }}" class="btn btn-info {{ request()->is('reservations/employees/send-schedule') ? 'active' : '' }}">Send Schedule Email</a>
        </div>
        @yield('content')
    </div>

    <div class="footer">
        <p style="padding-top: 40px;">
            This application is part of the technical assessment for the Backend Developer position. <br> Name: Carlos Fernandez | Email: fernandez.isc@gmail.com
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        document.getElementById('download-schedule-btn').addEventListener('click', function(event) {
            event.preventDefault();
            const url = this.href;

            fetch(url)
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'employee_schedule_report.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    iziToast.success({
                        position: 'topLeft',
                        title: 'Success',
                        message: 'The schedule has been downloaded successfully!',
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    iziToast.error({
                        title: 'Error',
                        message: 'There was an error downloading the schedule.',
                    });
                });
        });
    </script>
    @yield('scripts')
</body>
</html>
