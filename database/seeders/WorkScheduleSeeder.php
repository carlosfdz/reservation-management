<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkScheduleSeeder extends Seeder
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
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                if ($currentDate->isWeekday()) {
                    WorkSchedule::create([
                        'employee_id' => $employee->id,
                        'day_of_week' => $currentDate->format('l'),
                        'start_date' => $currentDate->toDateString(),
                        'end_date' => $currentDate->toDateString(),
                        'start_time' => '09:00:00',
                        'end_time' => '16:00:00',
                        'lunch_start_time' => '13:00:00',
                        'lunch_end_time' => '14:00:00',
                    ]);
                }
                $currentDate->addDay();
            }
        }
    }
}
