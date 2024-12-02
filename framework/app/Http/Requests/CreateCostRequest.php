<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCostRequest extends FormRequest
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
		$rules = [];
		if ($this->request->cost = []) {

			foreach ($this->request->get('cost') as $key => $val) {
				$rules['equipments.' . $key] = 'required';
				$rules['start_dates.' . $key] = 'required';
				$rules['end_dates.' . $key] = 'required|date|after:start_dates.' . $key;
				$rules['cost.' . $key] = 'required|numeric';
			}
		} else {
			// dd("fds");
			$rules['equipments'] = 'required';
			$rules['start_dates'] = 'required|date';
			$rules['end_dates'] = 'required|date|after:start_dates';
			$rules['cost'] = 'required|numeric';
		}

		// }
		$rules = [
			'tp_name' => 'required_if:cost_by,=,tp',
			'tp_mobile' => 'required_if:cost_by,=,tp|nullable|numeric',
			'tp_email' => 'required_if:cost_by,=,tp|nullable|email',
			'hospital_id' => 'required',
			'type' => 'required',
			'cost_by' => 'required',
		];
		return $rules;
	}

	public function messages()
{
    $messages = [
        'tp_name.required_if' => trans('validation.name_required_message'),
        'tp_mobile.required_if' => trans('validation.mobile_number_required_message'),
        'tp_mobile.numeric' => trans('validation.mobile_number_numeric_message'),
        'tp_email.required_if' => trans('validation.email_required_message'),
        'tp_email.email' => trans('validation.email_message'),
        'hospital_id.required' => trans('validation.hospital_id_required_message'),
        'cost_by.required' => trans('validation.cost_by_required_message'),
        'type.required' => trans('validation.type_required_message'),
        'end_dates.after' => 'end date must be after start date', // Ensure this translation key exists
    ];

    if ($this->has('cost') && is_array($this->input('cost'))) {
        foreach ($this->input('cost') as $key => $value) {
            $messages['equipments.' . $key . '.required'] = trans('validation.equip_id_required_message');
            $messages['start_dates.' . $key . '.required'] = trans('validation.start_date_required_message');
            $messages['end_dates.' . $key . '.required'] = trans('validation.end_date_required_message');
            $messages['end_dates.' . $key . '.after'] = trans('validation.end_date_after_message');
            $messages['cost.' . $key . '.required'] = trans('validation.cost_required_message');
            $messages['cost.' . $key . '.numeric'] = trans('validation.cost_number_message');
        }
    }

    return $messages;
}
}
