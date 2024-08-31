<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'day_of_week',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'lunch_start_time',
        'lunch_end_time',
    ];

    /**
     * Get the employee that owns the work schedule.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the reservations for the work schedule.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope a query to only include work schedules for a specific day of the week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $dayOfWeek
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDayOfWeek($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope a query to only include work schedules for a specific time range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startTime
     * @param  string  $endTime
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTimeRange($query, $startTime, $endTime)
    {
        return $query->where('start_time', '>=', $startTime)
                     ->where('end_time', '<=', $endTime);
    }

    /**
     * Scope a query to only include work schedules that have a lunch break.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasLunchBreak($query)
    {
        return $query->whereNotNull('lunch_start_time')
                     ->whereNotNull('lunch_end_time');
    }

    /**
     * Scope a query to only include work schedules that do not have a lunch break.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDoesNotHaveLunchBreak($query)
    {
        return $query->whereNull('lunch_start_time')
                     ->whereNull('lunch_end_time');
    }
}
