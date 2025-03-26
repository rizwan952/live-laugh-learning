<?php

namespace App\Http\Services\Admin;


use App\Http\Resources\Admin\AdminCalendarResource;
use App\Models\Calendar;
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
        }
        // Apply only start_date filter if end_date is not provided
        elseif ($request->has('startDate')) {
            $query->where('date', '>=', $request->input('startDate'));
        }
        // Apply only end_date filter if start_date is not provided
        elseif ($request->has('endDate')) {
            $query->where('date', '<=', $request->input('endDate'));
        }

        // Get the filtered results
        $calendars = $query->get();
        return AdminCalendarResource::collection($calendars);
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

}
