<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    protected $guarded = ['id'];

    public function orderPackageLessons()
    {
        return $this->hasMany(OrderPackageLesson::class);
    }
}
