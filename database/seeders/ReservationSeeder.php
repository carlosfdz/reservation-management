<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Reservation;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::parse('2024-09-01');
        $endDate = Carbon::parse('2024-10-31');

        foreach ($employees as $employee) {
            $currentMonth = $startDate->copy()->startOfMonth();
            $endMonth = $endDate->copy()->endOfMonth();

            while ($currentMonth <= $endMonth) {
                $weekStart = $currentMonth->copy()->startOfMonth();
                $weekEnd = $weekStart->copy()->addDays(6);
                if ($weekEnd->gt($currentMonth->copy()->endOfMonth())) {
                    $weekEnd = $currentMonth->copy()->endOfMonth();
                }

                $currentDate = $weekStart->copy();

                $reservationsCount = 0;
                while ($currentDate <= $weekEnd && $reservationsCount < 8) {
                    if ($currentDate->isWeekday()) {
                        $workSchedule = WorkSchedule::where('employee_id', $employee->id)
                            ->where('day_of_week', $currentDate->format('l'))
                            ->first();

                        if ($workSchedule) {
                            $lunchStart = Carbon::parse($workSchedule->lunch_start_time);
                            $lunchEnd = Carbon::parse($workSchedule->lunch_end_time);
                            $workStart = Carbon::parse($workSchedule->start_time);
                            $workEnd = Carbon::parse($workSchedule->end_time);

                            $availableSlots = [];
                            $reservationStart = $workStart->copy();
                            $reservationEnd = $reservationStart->copy()->addHour();
                            while ($reservationEnd <= $lunchStart) {
                                $availableSlots[] = [
                                    'start' => $reservationStart->copy(),
                                    'end' => $reservationEnd->copy()
                                ];
                                $reservationStart->addHour();
                                $reservationEnd->addHour();
                            }

                            $reservationStart = $lunchEnd->copy();
                            $reservationEnd = $reservationStart->copy()->addHour();
                            while ($reservationEnd <= $workEnd) {
                                $availableSlots[] = [
                                    'start' => $reservationStart->copy(),
                                    'end' => $reservationEnd->copy()
                                ];
                                $reservationStart->addHour();
                                $reservationEnd->addHour();
                            }

                            shuffle($availableSlots);
                            $selectedSlots = array_slice($availableSlots, 0, min(8 - $reservationsCount, count($availableSlots)));

                            foreach ($selectedSlots as $slot) {
                                if ($reservationsCount >= 8) {
                                    break 2;
                                }
                                Reservation::create([
                                    'employee_id' => $employee->id,
                                    'start_time' => $currentDate->copy()->setTimeFrom($slot['start'])->toDateTimeString(),
                                    'end_time' => $currentDate->copy()->setTimeFrom($slot['end'])->toDateTimeString(),
                                ]);
                                $reservationsCount++;
                            }
                        }
                    }
                    $currentDate->addDay();
                }

                $currentMonth->addMonth();
            }
        }
    }
}
