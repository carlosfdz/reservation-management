<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use DateTimeZone;
use App\Models\Reservation;

class EmployeeAvailabilityService
{
    /**
     * Find available employees for a given date and time in New York timezone.
     *
     * @param string $dateTime New York date and time to check availability
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableEmployees(string $dateTime)
    {
        $nyDateTime = Carbon::parse($dateTime, 'America/New_York');

        // $utcDateTime = $nyDateTime->copy()->setTimezone('UTC');
        $utcDateTime = $nyDateTime;
        // dd($utcDateTime);

        return Employee::whereDoesntHave('reservations', function ($query) use ($utcDateTime) {
            $query->where('start_time', '<=', $utcDateTime->toDateTimeString())
                  ->where('end_time', '>', $utcDateTime->toDateTimeString());
        })->whereHas('workSchedules', function ($query) use ($utcDateTime) {
            $query->where('start_date', '<=', $utcDateTime->toDateString())
                  ->where('end_date', '>=', $utcDateTime->toDateString())
                  ->where('start_time', '<=', $utcDateTime->toTimeString())
                  ->where('end_time', '>=', $utcDateTime->toTimeString())
                  ->where(function($subQuery) use ($utcDateTime) {
                      $subQuery->where('lunch_start_time', '>', $utcDateTime->toTimeString())
                               ->orWhere('lunch_end_time', '<=', $utcDateTime->toTimeString());
                  });
        })->get();
    }
}
