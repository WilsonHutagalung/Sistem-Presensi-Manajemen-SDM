<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'is_working_day',
    ];

    protected $casts = [
        'is_working_day' => 'boolean',
    ];
}
