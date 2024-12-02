<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportREquest extends FormRequest
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
            'columns' => [
                'required',
                'array',
                'min:1', // Ensure at least one column is selected
                function ($attribute, $value, $fail) {
                    if (count($value) === 0) {
                        $fail('At least one column must be selected.');
                    }
                },
            ],
            'title' => 'required|string',
            'subtitle' => 'required|string',
        ];
    }
}
