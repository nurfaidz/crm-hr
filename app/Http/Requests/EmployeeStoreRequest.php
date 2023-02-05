<?php

namespace App\Http\Requests;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EmployeeStoreRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|max:13',
            'place_of_birth' => 'required',
            'marital_status_id' => 'required',
            'date_of_birth' => 'required|date',
            'religion_id' => 'required',
            'blood_type' => 'required',
            'nik' => 'size:16|required',
            'passport' => 'max:7',
            'address' => 'required',
            'postal_code' => 'required|size:5',
            // 'facebook_link' => 'url',
            // 'instagram_link' => 'url',
            // 'twitter_link' => 'url',
            // 'linkedin_link' => 'url',
            // 'github_link' => 'url',
            'company_id' => 'required',
            'department_id' => 'required',
            'branch_id' => 'required',
            'employment_status_id' => 'required',
            'job_class_id' => 'required',
            'role_id' => 'required',
            'job_position_id' => 'required',
            'work_shift_id' => 'required',
            'date_of_joining' => 'required|date',
            'status' => 'required',
            'basic_salary' => 'required',
            'salary_type_id' => 'required',
            'bank_id' => 'required|numeric',
            'npwp' => 'required|min:15|max:17',
            'bank_account_number' => 'required|min:10|max:16',
            'bank_account_holder' => 'required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseFormatter::error($validator->errors(), 'Bad Request / Validation Errors'));
    }
}
