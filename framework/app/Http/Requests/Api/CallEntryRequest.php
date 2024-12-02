<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
class CallEntryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'equip_id' => 'required',
            'call_handle' => 'required',
            'report_no' => 'required_if:call_handle,==,external',
            'call_register_date_time' => 'required|date',
            'next_due_date' => 'required',
            'working_status' => 'required',
            'nature_of_problem' => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseData('0', 'Validation Errors',$validator->errors(), 401));
    }
}
