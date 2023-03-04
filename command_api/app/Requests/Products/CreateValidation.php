<?php declare(strict_types = 1);

namespace App\Requests\Products;

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
      'sku'         => 'required|unique:products|max:255',
      'name'        => 'required|max:255',
      'price'       => 'required|numeric|gt:0',
      'stock'       => 'required|numeric|gte:0',
      'categoryId'  => 'required',
    ];
  }

  public function messages(): array {
    return [
      'price.gt' => 'price must not negative',
      'stock.gte' => 'stock must not negative',
    ];
  }

  public function failedValidation(Validator $validator) {
    throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
    ], 400));
  }
}
