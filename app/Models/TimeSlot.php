<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'start_at' => 'datetime:H:i:s',
        'end_at' => 'datetime:H:i:s',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
