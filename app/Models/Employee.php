<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'time_zone_id',
    ];

    /**
     * Get the work schedules for the employee.
     */
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    /**
     * Get the reservations for the employee.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the time zone that belongs to the employee.
     */
    public function timeZone()
    {
        return $this->belongsTo(TimeZone::class);
    }

    /**
     * Scope a query to only include employees for a specific day of the week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $dayOfWeek
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDayOfWeek($query, $dayOfWeek)
    {
        return $query->whereHas('workSchedules', function ($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek);
        });
    }
}
