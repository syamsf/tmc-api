<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Requests\Categories\CreateValidation as CategoriesCreateValidation;
use App\Repositories\MariaDB\Categories as CategoriesRepo;
use App\Helpers\Engine\Categories as EngineCategories;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\Controller;

class Categories extends Controller {
  public function create(CategoriesCreateValidation $request): array {
    try {
      $name = $request->input('name');

      $categoriesRepo   = new CategoriesRepo();
      $engineCategories = new EngineCategories($categoriesRepo);
      $result = $engineCategories->createData($name);

      return ['data' => $result];
    } catch (\Exception $e) {
      throw new HttpResponseException(response()->json([
        'errors' => ['message' => $e->getMessage()]
      ], 400));
    }
  }
}
