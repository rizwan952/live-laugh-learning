<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDuration extends Model
{
    public function packages()
    {
        return $this->hasMany(CoursePackage::class);
    }
}
