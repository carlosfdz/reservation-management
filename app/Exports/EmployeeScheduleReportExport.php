<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeScheduleReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with(['workSchedules', 'reservations'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Total Hours Available',
            'Work Schedule Details (Date Start_Time - End_Time)',
            'Total Hours Reserved',
            'Reservation Details (Date Start_Time - End_Time)',
        ];
    }

    /**
     * Applies a callback function to each element of an array and returns a new array with the results.
     *
     * @param callable $callback The callback function to apply to each element.
     * @param array $array The array to be mapped.
     * @return array The new array with the results of the callback function applied to each element.
     */
    public function map($employee): array
    {
        $availableHours = 0;
        $availableSlots = [];

        foreach ($employee->workSchedules as $schedule) {
            $preLunchStart = Carbon::parse($schedule->start_date . ' ' . $schedule->start_time);
            $preLunchEnd = Carbon::parse($schedule->start_date . ' ' . $schedule->lunch_start_time);

            $postLunchStart = Carbon::parse($schedule->start_date . ' ' . $schedule->lunch_end_time);
            $postLunchEnd = Carbon::parse($schedule->start_date . ' ' . $schedule->end_time);

            $preLunchAvailable = $this->calculateAvailableSlots($preLunchStart, $preLunchEnd, $employee->reservations);
            $availableSlots = array_merge($availableSlots, $preLunchAvailable);

            $postLunchAvailable = $this->calculateAvailableSlots($postLunchStart, $postLunchEnd, $employee->reservations);
            $availableSlots = array_merge($availableSlots, $postLunchAvailable);

            $availableHours += $this->calculateAvailableHoursFromSlots($preLunchAvailable) + $this->calculateAvailableHoursFromSlots($postLunchAvailable);
        }

        $availableSlotsFormatted = collect($availableSlots)->map(function($slot) {
            return $slot['start']->format('Y-m-d H:i') . ' - ' . $slot['end']->format('H:i');
        })->implode(', ');

        $totalReservations = $employee->reservations->count();

        $reservations = $employee->reservations->where('employee_id', $employee->id)->map(function($reservation) {
            $startTime = Carbon::parse($reservation->start_time)->format('Y-m-d H:i:s');
            $endTime = Carbon::parse($reservation->end_time)->format('H:i:s');
            return $startTime . ' - ' . $endTime;
        })->implode(', ');

        return [
            $employee->first_name,
            $employee->last_name,
            $availableHours,
            $availableSlotsFormatted,
            $totalReservations,
            $reservations,
        ];
    }

    /**
     * Calculates the available slots based on the given parameters.
     *
     * @param int $totalSlots The total number of slots.
     * @param int $occupiedSlots The number of occupied slots.
     * @return int The number of available slots.
     */
    private function calculateAvailableSlots($start, $end, $reservations)
    {
        $availableSlots = [];

        $currentSlotStart = $start;

        foreach ($reservations as $reservation) {
            $reservationStart = Carbon::parse($reservation->start_time);
            $reservationEnd = Carbon::parse($reservation->end_time);

            if ($reservationStart < $end && $reservationEnd > $start) {
                if ($reservationStart > $currentSlotStart) {
                    $availableSlots = array_merge($availableSlots, $this->splitIntoHourlySlots($currentSlotStart, $reservationStart));
                }

                $currentSlotStart = $reservationEnd;
            }
        }

        if ($currentSlotStart < $end) {
            $availableSlots = array_merge($availableSlots, $this->splitIntoHourlySlots($currentSlotStart, $end));
        }

        return $availableSlots;
    }

    /**
     * Splits a given time range into hourly slots.
     *
     * @param string $startTime The start time of the range in "HH:MM" format.
     * @param string $endTime The end time of the range in "HH:MM" format.
     * @return array An array of hourly slots within the given time range.
     */
    private function splitIntoHourlySlots($start, $end)
    {
        $hourlySlots = [];
        $current = $start->copy();

        while ($current < $end) {
            $next = $current->copy()->addHour();
            if ($next > $end) {
                $next = $end;
            }
            $hourlySlots[] = [
                'start' => $current,
                'end' => $next
            ];
            $current = $next;
        }

        return $hourlySlots;
    }

    /**
     * Calculates the available hours from the given slots.
     *
     * This function takes an array of time slots and calculates the total available hours
     * by summing up the duration of each slot. The slots should be in the format of
     * ['start' => 'HH:MM', 'end' => 'HH:MM'].
     *
     * @param array $slots An array of time slots.
     * @return int The total available hours.
     */
    private function calculateAvailableHoursFromSlots($slots)
    {
        $totalHours = 0;

        foreach ($slots as $slot) {
            $totalHours += $slot['end']->diffInMinutes($slot['start']) / 60;
        }

        return $totalHours;
    }
}
