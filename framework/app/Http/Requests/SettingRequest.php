<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest {
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
			'yb_type' => 'required',
			'private_site' => 'required',
			'allow_contribution' => 'required',
		];
	}
	public function messages() {
		return [
			'yb_type.required' => trans('validation.yb_type_required_message'),
			'private_site.required' => trans('validation.private_site_required_message'),
			'allow_contribution.required' => trans('validation.allow_contribution_required_message')
		];
	}
}
