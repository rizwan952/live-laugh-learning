<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackageLesson extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class);
    }
}
