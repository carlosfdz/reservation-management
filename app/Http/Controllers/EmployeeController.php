<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Mail\EmployeeScheduleMail;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    /**
     * Shows the form for sending a schedule email.
     *
     * @return \Illuminate\View\View The view for sending a schedule email.
     */
    public function showSendScheduleForm()
    {
        $employees = Employee::all();

        return view('employees.schedule-management', compact('employees'));
    }

    /**
     * Sends a schedule email to the specified recipient.
     *
     * @param string $recipient The email address of the recipient.
     * @param string $schedule The schedule to be included in the email.
     * @param string $subject The subject of the email.
     * @param string $from The email address of the sender.
     * @return bool True if the email was sent successfully, false otherwise.
     */
    public function sendScheduleEmail(Request $request)
    {
        $employeeId = $request->input('employee_id');
        // $employeeId = 1;
        $email = $request->input('email');
        // $email = 'fernandez.isc@gmail.com';
        $date = $request->input('date');
        // $date = '2024-09-02';

        $employee = Employee::with('workSchedules', 'reservations')->findOrFail($employeeId);

        // Calculate the hourly schedule based on the employee's work schedules.
        // $hourlySchedule = $this->calculateHourlySchedule($employee, $date);

        // Calculate the hourly schedule based on the employee's reservations.
        $hourlySchedule = $this->calculateHourlyScheduleFromReservations($employee, $date);

        Mail::to($email)->send(new EmployeeScheduleMail($employee, $date, $hourlySchedule));

        return back()->with('success', 'Email sent successfully!');
    }

    /**
     * Calculates the hourly schedule based on the given parameters.
     *
     * @param int $startHour The starting hour of the schedule.
     * @param int $endHour The ending hour of the schedule.
     * @param int $interval The interval between each hour in the schedule.
     * @return array The array containing the hourly schedule.
     */
    private function calculateHourlySchedule($employee, $date)
    {
        $hourlySchedule = [];

        foreach ($employee->workSchedules as $schedule) {
            if (Carbon::parse($schedule->start_date)->isSameDay($date)) {
                $start = Carbon::parse($schedule->start_time);
                $end = Carbon::parse($schedule->end_time);
                $lunchStart = Carbon::parse($schedule->lunch_start_time);
                $lunchEnd = Carbon::parse($schedule->lunch_end_time);

                while ($start < $end) {
                    $nextHour = $start->copy()->addHour();
                    if ($nextHour > $end) {
                        $nextHour = $end;
                    }

                    if (!($start >= $lunchStart && $start < $lunchEnd)) {
                        $hourlySchedule[] = $start->format('H:i') . ' - ' . $nextHour->format('H:i');
                    }

                    $start = $nextHour;
                }
            }
        }

        return $hourlySchedule;
    }

    /**
     * Calculates the hourly schedule from a list of reservations.
     *
     * This function takes a list of reservations and calculates the hourly schedule based on the start and end times of each reservation.
     * The resulting schedule is returned as an array, where each element represents an hour and contains the number of reservations scheduled for that hour.
     *
     * @param array $reservations The list of reservations.
     * @return array The hourly schedule.
     */
    private function calculateHourlyScheduleFromReservations($employee, $date)
    {
        $hourlySchedule = [];
        $date = Carbon::parse($date);

        foreach ($employee->reservations as $reservation) {
            $reservationDate = Carbon::parse($reservation->start_time);

            if ($reservationDate->isSameDay($date)) {
                $start = $reservationDate;
                $end = Carbon::parse($reservation->end_time);

                while ($start < $end) {
                    $nextHour = $start->copy()->addHour();
                    if ($nextHour > $end) {
                        $nextHour = $end;
                    }

                    $hourlySchedule[] = $start->format('H:i') . ' - ' . $nextHour->format('H:i');

                    $start = $nextHour;
                }
            }
        }

        sort($hourlySchedule);

        return $hourlySchedule;
    }
}
