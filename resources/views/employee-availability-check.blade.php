@extends('layouts.app')

@section('title', 'Check Employee Availability by Specific Date and Time')

@section('content')
    <h1 class="mb-4">Check Employee Availability by Specific Date and Time</h1>
    <p class="description">
        This is Exercise 3.
    </p>
    <p class="description">
        This API is designed to determine which employees are available at a specific date and time.
    </p>
    <p class="description">
        By providing a date and time in the format 'YYYY-MM-DD HH:MM:SS', the system will search the database for employees who do not have reservations and are within their scheduled work hours for that moment. Note that the availability check also considers employees' lunch breaks, so if an employee has a lunch break during the specified time, they will be excluded from the availability list.
    </p>
    <p class="description" style="padding-bottom: 20px;">
        The response will include a list of employees who are available at the specified date and time, excluding any periods during their lunch breaks. This helps you quickly identify who is free at the given moment, taking into account all scheduled work hours and breaks.
    </p>
    <form id="availabilityCheckForm">
        <div class="form-group">
            <label for="date_time">Date and Time:</label>
            <input type="text" class="form-control" id="date_time" name="date_time" placeholder="YYYY-MM-DD HH:MM:SS" required>
        </div>
        <button type="submit" class="btn btn-primary">Check Availability</button>
    </form>
    <div id="results" class="mt-4"></div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            flatpickr("#date_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
            });

            $('#availabilityCheckForm').on('submit', function(e) {
                e.preventDefault();

                var dateTime = $('#date_time').val();

                $.ajax({
                    url: '/api/employees/availability',
                    method: 'GET',
                    data: {
                        date_time: dateTime
                    },
                    success: function(response) {
                        var resultsHtml = '<h2>Available Employees:</h2><ul>';
                        if (response.available_employees.length > 0) {
                            response.available_employees.forEach(function(employee) {
                                resultsHtml += '<li>' + employee.first_name + ' ' + employee.last_name + '</li>';
                            });
                        } else {
                            resultsHtml += '<li class="text-danger">No employees available at this time.</li>';
                        }
                        resultsHtml += '</ul>';
                        $('#results').html(resultsHtml);
                    },
                    error: function(xhr) {
                        $('#results').html('<p class="text-danger">An error occurred: ' + xhr.statusText + '</p>');
                    }
                });
            });
        });
    </script>
@endsection
