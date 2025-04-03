<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'totalSales' => $this['total_sales'],
            'currentMonthSales' => $this['current_month_sales'],
            'dailySales' => $this['dailySales']->map(function ($course) {
                return [
                    'date' => $course->date,
                    'total_sales' => $course->total_sales,
                ];
            }),
            'courseSales' => $this['course_sales']->map(function ($course) {
                return [
                    'courseId' => $course->course_id,
                    'courseName' => $course->course_name,
                    'totalOrders' => $course->total_orders,
                    'totalSales' => $course->total_sales,
                ];
            }),
        ];
    }
}
