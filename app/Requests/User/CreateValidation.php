<?php declare(strict_types = 1);

namespace App\Requests\User;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateValidation extends FormRequest {
  /**
   * Determine if the user is authorize to make a request
   *
   * @return boolean
   */
  public function authorize(): bool {
    return true;
  }

  /**
   * Get the validation rules that apply to the request
   *
   * @return array
   */
  public function rules(): array {
    return [
      'name'     => 'required|max:255',
      'email'    => 'required',
      'password' => 'required'
    ];
  }

  public function failedValidation(Validator $validator) {
    throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
    ], 400));
  }
}
