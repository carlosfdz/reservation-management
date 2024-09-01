<!DOCTYPE html>
<html>
<head>
    <title>Employee Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #333333;
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #e9ecef;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            font-size: 16px;
            color: #333333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reserved Schedules for {{ $employee->first_name }} {{ $employee->last_name }} on {{ $date }}</h2>
        <ul>
            @if (count($hourlySchedule) > 0)
                @foreach ($hourlySchedule as $hour)
                    <li>🕒 {{ $hour }}</li>
                @endforeach
            @else
                <li>No schedules available for this date.</li>
            @endif
        </ul>
        <div class="footer">
            @if (count($hourlySchedule) > 0)
                <span>
                    <a href="#">
                        Schedule in Google Calendar
                    </a>
                </span>
            @endif
            <p style="padding-top: 20px;">
                This email is part of the technical assessment for the Backend Developer position. <br> Name: Carlos Fernandez | Email: fernandez.isc@gmail.com
            </p>
        </div>
    </div>
</body>
</html>
