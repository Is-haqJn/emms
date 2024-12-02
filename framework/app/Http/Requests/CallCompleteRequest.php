<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallCompleteRequest extends FormRequest {
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
			'call_complete_date_time' => 'required',
			'next_due_date' => 'required|date',
			'service_rendered' => 'required',
			'remarks' => 'required',
			'working_status' => 'required',
			'sign_of_engineer' => 'required|file|mimes:jpg,jpeg,png,pdf',
		];
	}
}
