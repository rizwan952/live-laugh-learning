<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCalendarRequest;
use App\Http\Requests\AdminCalendarUpdateRequest;
use App\Http\Services\Admin\CalendarService;
use App\Models\Calendar;
use App\Models\TimeSlot;
use App\Traits\ApiResponseHelper;
use Exception;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    use ApiResponseHelper;
    protected $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->calendarService->getCalendar($request);
            return $this->apiResponse(true, 'Calendar fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function store(AdminCalendarRequest $request)
    {
        try {
            $this->calendarService->createCalendar($request);
            return $this->apiResponse(true, 'Calendar slots created successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function updateTimeSlot(AdminCalendarUpdateRequest $request, TimeSlot $timeSlot)
    {
        try {
            $this->calendarService->updateTimeSlot($request, $timeSlot);
            return $this->apiResponse(true, 'Calendar slots updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function deleteTimeSlot(TimeSlot $timeSlot)
    {
        try {
            $this->calendarService->deleteTimeSlot($timeSlot);
            return $this->apiResponse(true, 'Calendar slots deleted successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }
}
