<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the employee that owns the reservation.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the work schedule that belongs to the reservation.
     */
    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }

    /**
     * Scope a query to only include reservations for a specific day of the week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $dayOfWeek
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDayOfWeek($query, $dayOfWeek)
    {
        return $query->whereHas('workSchedule', function ($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek);
        });
    }
}
