<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendCallRequest extends FormRequest {
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
			'call_attend_date_time' => 'required',
			'user_attended' => 'required',
			'service_rendered' => 'required',
			'remarks' => 'required',
			'working_status' => 'required',
		];
	}
}
