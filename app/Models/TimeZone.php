<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'offset'
    ];

    /**
     * Get the employees for the time zone.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
