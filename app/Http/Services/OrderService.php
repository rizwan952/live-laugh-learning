<?php

namespace App\Http\Services;

use App\Models\CoursePackage;
use App\Models\Order;
use App\Models\OrderPackage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function createOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $package = CoursePackage::find($request->package_id);
            $order = Order::create([
                'student_id' => $request->user()->id,
                'course_id' => $package->duration->course_id,
                'course_name' => $package->duration->course->name,
                'course_price' => $package->price,
                'final_amount' => $package->price
            ]);
            OrderPackage::create([
                'order_id' => $order->id,
                'duration_id' => $package->duration->id,
                'package_id' => $package->id,
                'duration' => $package->duration->duration,
                'type' => $package->type,
                'price' => $package->price,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


}
