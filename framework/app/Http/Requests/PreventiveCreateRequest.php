<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreventiveCreateRequest extends FormRequest {
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
		return [
			'equip_id' => 'required',
			'call_handle' => 'required',
			// 'report_no' => 'required_if:call_handle,==,external|integer',
			'report_no' => 'required_if:call_handle,==,external',
			'call_register_date_time' => 'required|date',
			'next_due_date' => 'required|date|after:call_register_date_time',
			'working_status' => 'required',
			'nature_of_problem' => 'required',
		];
	}
	public function messages() {
		return [
			'equip_id.required' => trans('validation.equip_id_required_message'),
		];
	}
}
