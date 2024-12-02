<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class ProfilePicRequest extends FormRequest
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
      'image' => 'required|image|mimes:jpeg,png,jpg,gif',
    ];
  }
  public function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(responseData('0', 'Validation Errors',$validator->errors(), 401));
  }
}
