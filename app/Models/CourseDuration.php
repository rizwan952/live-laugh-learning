<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDuration extends Model
{
    protected $guarded = ['id'];
    public function packages()
    {
        return $this->hasMany(CoursePackage::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
