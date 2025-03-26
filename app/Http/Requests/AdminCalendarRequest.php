<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminCalendarRequest extends FormRequest
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
            'timeZone' => ['required', 'timezone'], // Ensures it's a valid timezone
            'date' => ['required', 'date_format:Y-m-d'], // Ensures date is in YYYY-MM-DD format
            'slots' => ['required', 'array', 'min:1'], // Ensures at least one slot is provided
            'slots.*.startAt' => ['required', 'date_format:H:i:s', function ($attribute, $value, $fail) { // Ensures time is in HH:MM:SS format
                $index = explode('.', $attribute)[1];
                $endAt = $this->input("slots.$index.endAt");
                if ($endAt && $value >= $endAt) {
                    $fail("The {$attribute} must be earlier than the corresponding end time.");
                }
            }],
            'slots.*.endAt' => ['required', 'date_format:H:i:s'], // Ensures time is in HH:MM:SS format
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
