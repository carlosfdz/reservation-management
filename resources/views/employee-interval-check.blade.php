@extends('layouts.app')

@section('title', 'Check Employee Availability by Interval')

@section('content')
    <h1 class="mb-4">Check Employee Availability by Interval</h1>
    <p class="description">
        This is Exercise 2.
    </p>
    <p class="description">
        This feature allows you to check the availability of employees within a specific time interval, considering both their work schedules and reservations.
    </p>
    <p class="description">
        By entering a start and end date and time, the application will query the database to identify which employees have work schedules overlapping with the provided interval.
    </p>
    <p class="description" style="padding-bottom: 20px;">
        The results will display a list of employees who are available throughout the entire specified interval, ensuring that all the time within the interval is covered by their work hours, including any reservations they might have.
    </p>
    <form id="availabilityForm">
        <div class="form-group">
            <label for="start_date_time">Start Date Time:</label>
            <input type="text" class="form-control" id="start_date_time" name="start_date_time" placeholder="YYYY-MM-DD HH:MM:SS" required>
        </div>
        <div class="form-group">
            <label for="end_date_time">End Date Time:</label>
            <input type="text" class="form-control" id="end_date_time" name="end_date_time" placeholder="YYYY-MM-DD HH:MM:SS" required>
        </div>
        <button type="submit" class="btn btn-primary">Check Availability</button>
    </form>
    <div id="results" class="mt-4"></div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            flatpickr("#start_date_time, #end_date_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
            });

            $('#availabilityForm').on('submit', function(e) {
                e.preventDefault();

                var startDateTime = $('#start_date_time').val();
                var endDateTime = $('#end_date_time').val();

                $.ajax({
                    url: '/api/employees/availability/interval',
                    method: 'GET',
                    data: {
                        start_date_time: startDateTime,
                        end_date_time: endDateTime
                    },
                    success: function(response) {
                        var resultsHtml = '<h2>Available Employees:</h2><ul>';
                        if (response.employees.length > 0) {
                            response.employees.forEach(function(employee) {
                                resultsHtml += '<li>' + employee.first_name + ' ' + employee.last_name + '</li>';
                            });
                        } else {
                            resultsHtml += '<li class="text-danger">No employees available in this interval.</li>';
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
