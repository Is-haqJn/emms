<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Hospital;

class HospitalRequest extends FormRequest {
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
	public function rules(Request $request) {
		
		
			$rules=[
			'name' => 'required|unique:hospitals,name,'.$request->id,
			'email' => 'required',
			'contact_person' => 'required',
			'phone_no' => 'required|numeric|min:6',
			'mobile_no' => 'required|numeric|min:10',
			'address' => 'required',
	     	'slug'=>'required|max:8|unique:hospitals,slug,'.$request->id,
			];
			return $rules;
		}
	public function messages() {
		return [
			'mobile_no.required' => trans('validation.mobile_number_required_message'),
			'phone_no.required' =>  trans('validation.phone_number_required_message'),
			'slug.required'=> 'short name is required',
			'slug.max'=>trans('validation.slug_max_message'),
			'slug.unique'=>trans('validation.slug_unique_message'),
		];
	}
}
