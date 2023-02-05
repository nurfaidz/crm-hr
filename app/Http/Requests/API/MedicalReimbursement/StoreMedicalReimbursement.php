<?php

namespace App\Http\Requests\API\MedicalReimbursement;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreMedicalReimbursement extends FormRequest
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
        return 
            [
            'category' => 'required',
            'outpatient_type' => 'required_if:category,==,1',
            'amount' => 'required',
            'notes' => 'required'
            ];
            [
            'outpatient_type.required_if' => 'The outpatient type field is required.'
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
