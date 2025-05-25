<?php

namespace App\Http\Services\Admin;


use App\Http\Resources\Admin\AdminCalendarResource;
use App\Models\Calendar;
use App\Models\OrderPackageLesson;
use App\Models\TimeSlot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarService
{

    public function getCalendar(Request $request)
    {
        // Start the query with eager loading of time slots
        $query = Calendar::query()->with('timeSlots');

        // Apply date range filter if both start_date and end_date are provided
        if ($request->has('startDate') && $request->has('endDate')) {
            $query->whereBetween('date', [
                $request->input('startDate'),
                $request->input('endDate')
            ]);
        } // Apply only start_date filter if end_date is not provided
        elseif ($request->has('startDate')) {
            $query->where('date', '>=', $request->input('startDate'));
        } // Apply only end_date filter if start_date is not provided
        elseif ($request->has('endDate')) {
            $query->where('date', '<=', $request->input('endDate'));
        }

        // Get the filtered results
        $calendars = $query->get();

        // Fetch matching order_package_lessons manually
        $calendarDates = $calendars->pluck('date')->toArray();
        $bookedSlots = OrderPackageLesson::whereIn(DB::raw('DATE(start_at)'), $calendarDates)
            ->with('order.student')
            ->where('status', 'processing')
            ->whereNotNull('start_at')
            ->get();
        $slotsByDate = $bookedSlots->groupBy(function ($slot) {
            return date('Y-m-d', strtotime($slot->start_at));
        });

        $calendarData = $calendars->map(function ($calendar) use ($slotsByDate) {
            // Convert $calendar->date to a string in 'Y-m-d' format
            $dateKey = $calendar->date instanceof \Carbon\Carbon
                ? $calendar->date->toDateString()
                : (string)$calendar->date;
            $calendar->bookedSlots = $slotsByDate->get($dateKey, collect())->values();
            return $calendar;
        });

        return AdminCalendarResource::collection($calendarData);
    }

    public function createCalendar(Request $request)
    {
        try {
            DB::beginTransaction();
            // Create the calendar
            $calendar = Calendar::create([
                'time_zone' => $request->timeZone,
                'date' => $request->date,
            ]);

            // Create the time slots
            foreach ($request->slots as $slot) {
                $calendar->timeSlots()->create([
                    'start_at' => $slot['startAt'],
                    'end_at' => $slot['endAt'],
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

    public function updateTimeSlot(Request $request, TimeSlot $timeSlot)
    {
        try {
            DB::beginTransaction();
            $timeSlot->update([
                'start_at' => $request->startAt,
                'end_at' => $request->endAt,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

    public function deleteTimeSlot(TimeSlot $timeSlot)
    {
        try {
            DB::beginTransaction();
            $timeSlot->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

}
