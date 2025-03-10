<?php

namespace App\Http\Services;

use App\Models\CoursePackage;
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


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


}
