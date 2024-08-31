<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkSchedule>
 */
class WorkScheduleFactory extends Factory
{
    protected $model = WorkSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'day_of_week' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'start_time' => $this->faker->time('H:i:s', '09:00:00'),
            'end_time' => $this->faker->time('H:i:s', '16:00:00'),
            'lunch_start_time' => $this->faker->time('H:i:s', '13:00:00'),
            'lunch_end_time' => $this->faker->time('H:i:s', '14:00:00'),
        ];
    }
}
