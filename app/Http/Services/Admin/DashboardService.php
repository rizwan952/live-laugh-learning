<?php

namespace App\Http\Services\Admin;

use App\Http\Resources\Admin\SalesReportResource;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{

    public function getDashboard()
    {
        $totalSales = Order::where('payment_status', 'completed')->sum('final_amount');

        $currentMonthSales = Order::where('payment_status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('final_amount');

        $courseSales = Order::where('payment_status', 'completed')
            ->select('course_id', 'course_name', DB::raw('COUNT(id) as total_orders'), DB::raw('SUM(final_amount) as total_sales'))
            ->groupBy('course_id', 'course_name')
            ->orderByDesc('total_sales')
            ->get();

        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(final_amount) as total_sales')
        )
            ->where('payment_status', 'completed') // Considering only completed orders
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $salesData = [
            'total_sales' => $totalSales,
            'current_month_sales' => $currentMonthSales,
            'course_sales' => $courseSales,
            'dailySales'=>$dailySales
        ];
        return new SalesReportResource($salesData);

    }


}
