<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ProfileService;
use App\Models\User;
use App\Traits\ApiResponseHelper;
use Illuminate\Http\Request;
use Exception;
class ProfileController extends Controller
{
    use ApiResponseHelper;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }


    public function getProfile(Request $request)
    {
        try {
            $data = $this->profileService->getProfile($request);
            return $this->apiResponse(true, 'Data fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            $this->profileService->updateProfile($request);
            return $this->apiResponse(true, 'Data updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

}
