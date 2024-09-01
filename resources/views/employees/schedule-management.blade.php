@extends('layouts.app')

@section('title', 'Send Employee Schedule Email')

@section('content')
    <h1 class="mb-4">Send Employee Schedule Email</h1>
    <p class="description" style="padding-bottom: 20px;">
        This interface allows you to send an email with the full-day schedule of an employee.
    </p>
    <form id="sendScheduleForm" method="POST" action="{{ route('reservations.employees.send_schedule_email') }}">
        @csrf
        <div class="form-group">
            <label for="employee">Select Employee:</label>
            <select class="form-control" id="employee" name="employee_id" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" data-email="{{ $employee->email }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter employee email" required>
            <div class="alert alert-info mt-2" role="alert">
                You can change the email address for testing.
            </div>
        </div>
        <div class="form-group">
            <label for="date">Select Date:</label>
            <input type="text" class="form-control" id="date" name="date" placeholder="YYYY-MM-DD" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Schedule Email</button>
    </form>
    <div id="results" class="mt-4"></div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#date', {
                dateFormat: 'Y-m-d',
            });

            document.getElementById('employee').addEventListener('change', function() {
                const selectedEmployee = this.options[this.selectedIndex];
                document.getElementById('email').value = selectedEmployee.getAttribute('data-email');
            });

            const initialEmployee = document.getElementById('employee').options[0];
            document.getElementById('email').value = initialEmployee.getAttribute('data-email');

            @if(session('success'))
                iziToast.success({
                    title: 'Success',
                    message: '{{ session('success') }}',
                    position: 'topRight',
                });
            @endif
        });
    </script>
@endsection
