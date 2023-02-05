<?php

namespace App\Http\Requests;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\WorkShift;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OvertimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $date = Carbon::tomorrow();
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $workshift = Workshift::where('work_shift_id', $employee->work_shift_id)->firstOrFail();
        return [
            'date' => "required|date_format:Y-m-d|after:yesterday",
            'start_time' => "required|date_format:H:i:s|after:$workshift->end_time",
            'end_time' => "required|date_format:H:i:s|after:start_time|before:$date",
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'max_query.min' => 'Exception the parameter is leading to zero values, The max query must be at least 1.'
        ];
    }

    /**
     * Throw new error response.
     *
     * @return array
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseFormatter::error($validator->errors(), 'Bad Request / Validation Errors'));
    }
}
