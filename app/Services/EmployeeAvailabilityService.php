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
     * Find employees who work in a given time interval, considering their reservations and availability.
     *
     * @param string $startDateTime Start date and time of the interval (New York timezone)
     * @param string $endDateTime End date and time of the interval (New York timezone)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeesWorkingInInterval(string $startDateTime, string $endDateTime)
    {
        // dd($startDateTime, $endDateTime); // 2024-09-04 09:00:00, 2024-09-04 10:00:00
        $startNY = Carbon::parse($startDateTime, 'America/Mexico_City');
        $endNY = Carbon::parse($endDateTime, 'America/Mexico_City');

        $startUTC = $startNY->copy()->setTimezone('America/New_York');
        $endUTC = $endNY->copy()->setTimezone('America/New_York');
        // dd($startUTC, $endUTC); // 2024-09-04 11:00:00, 2024-09-04 12:00:00

        return Employee::where(function ($query) use ($startUTC, $endUTC) {
            $query->whereHas('workSchedules', function ($query) use ($startUTC, $endUTC) {
                $query->where(function ($subQuery) use ($startUTC, $endUTC) {
                    $subQuery->where('start_date', '<=', $endUTC->toDateString())
                             ->where('end_date', '>=', $startUTC->toDateString())
                             ->where(function ($timeQuery) use ($startUTC, $endUTC) {
                                 $timeQuery->where('start_time', '<=', $endUTC->toTimeString())
                                           ->where('end_time', '>', $startUTC->toTimeString());
                             });
                });
            })
            ->orWhereHas('reservations', function ($query) use ($startUTC, $endUTC) {
                $query->where(function ($subQuery) use ($startUTC, $endUTC) {
                    $subQuery->where('start_time', '<', $endUTC->toDateTimeString())
                             ->where('end_time', '>', $startUTC->toDateTimeString());
                });
            });
        })->get();
    }

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
