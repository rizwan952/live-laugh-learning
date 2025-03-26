<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
