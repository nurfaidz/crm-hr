<?php

namespace App\Http\Requests;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class ManualAttendanceGetRequest extends FormRequest
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
        return [
            'date' => 'required|date_format:Y-m-d',
            'check_in' => 'required|date_format:H:i:s',
            'check_out' => 'required|date_format:H:i:s|after:check_in',
            'photo' => 'required | mimes:pdf,jpg,png|max:5000'
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
