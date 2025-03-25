<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderLessonRequest extends FormRequest
{
    use ApiResponseHelper;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'packageLessons' => 'required|array|min:1', // Must be an array with at least 1 item
            'packageLessons.*.id' => 'required|exists:order_package_lessons,id', // Each ID must exist
            'packageLessons.*.startAt' => 'required|date_format:Y-m-d H:i:s', // Timestamp format
            'packageLessons.*.endAt' => 'required|date_format:Y-m-d H:i:s|after_or_equal:packageLessons.*.startAt', // Timestamp, >= startAt
            'packageLessons.*.timeZone' => 'required|timezone', // O
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->apiResponse(
            false,
            $validator->errors()->first(),
            $validator->errors()->toArray(),
            400
        );
        throw new HttpResponseException($response);
    }
}
