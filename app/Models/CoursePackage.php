<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackage extends Model
{
    protected $guarded = ['id'];

    public function duration()
    {
        return $this->belongsTo(CourseDuration::class,'course_duration_id');
    }

}
