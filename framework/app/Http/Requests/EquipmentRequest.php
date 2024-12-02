<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class EquipmentRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		// dd($request->all());
		$dateFormat = env('date_convert', 'Y-m-d');
		return [
			'name' => 'required',
			'short_name' => 'required',
			'hospital_id' => 'required',
			'sr_no' => 'required|regex:/^\S*$/',
			'model' => 'required',
			'department' => 'required',
			'company' => 'required',
			'date_of_purchase' => 'required',
			// 'order_date'=>'before_or_equal:date_of_purchase',
			// 'date_of_installation'=>'after_or_equal:date_of_purchase',
			// 'warranty_due_date'=>'after_or_equal:date_of_purchase',
			'order_date' => "before_or_equal:date_of_purchase|date_format:$dateFormat",
			'date_of_installation' => "after_or_equal:date_of_purchase|date_format:$dateFormat",
			'warranty_due_date' => "after_or_equal:date_of_purchase|date_format:$dateFormat",
			'service_engineer_no' => 'required|numeric',
		];
	}
	public function messages() {
		
			return [
				'hospital_id.required' => trans('validation.hospital_id_required_message'),
				'sr_no.required' => trans('validation.sr_no_required_message'),
				'date_of_purchase.required' => trans('validation.date_of_purchase_required_message'),
				'service_engineer_no.required' => trans('validation.service_engineer_no_required_message'),
				'sr_no.regex' => trans('validation.sr_no_regex_message'),
				'service_engineer_no.numeric' => trans('validation.service_engineer_no_numeric_message'),
			];
		
		
	}
	protected function failedValidation(Validator $validator)
	{
		// Check if it's an API request based on the 'Accept' header
		if ($this->is('api/*')) {
			throw new HttpResponseException(responseData('0', 'Validation Errors', '', 401));
		}

		// For non-API requests, use the default behavior
		parent::failedValidation($validator);
	}

}
