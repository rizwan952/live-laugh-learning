<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function package()
    {
        return $this->hasOne(OrderPackage::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

}
