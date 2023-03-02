<?php declare(strict_types = 1);

namespace App\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
      'name' => 'required|unique:categories|max:255',
    ];
  }
}
