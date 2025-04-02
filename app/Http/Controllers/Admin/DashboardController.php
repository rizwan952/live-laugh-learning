<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DashboardResource;
use App\Http\Services\Admin\DashboardService;
use App\Models\Category;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Tag;
use App\Traits\ApiResponseHelper;
use Exception;

class DashboardController extends Controller
{
    use ApiResponseHelper;
    /**
     * Display a listing of the resource.
     */

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        try {


            $data = $this->dashboardService->getDashboard();
            return $this->apiResponse(true, 'Data fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function getResources()
    {
        try {
            $resources =  [
                'languages' => Language::all(),
                'languageLevels' => LanguageLevel::all(),
                'categories' => Category::all(),
                'tags' => Tag::all()
            ];

            $data = new DashboardResource($resources);
            return $this->apiResponse(true, 'Data fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }


}
