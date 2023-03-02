<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Requests\Categories\CreateValidation as CategoriesCreateValidation;
use App\Helpers\Engine\Categories as EngineCategories;
use App\Repositories\MariaDB\Categories as MariaDBCategories;

class Categories extends Controller {
  public function create(CategoriesCreateValidation $request): array {
    try {
      $name = $request->input('name');

      $categoriesRepo   = new MariaDBCategories();
      $engineCategories = new EngineCategories($categoriesRepo);
      $result = $engineCategories->createData($name);

      return ['data' => $result];
    } catch (\Exception $e) {
      return [
        'status'  => 'failed',
        'message' => $e->getMessage()
      ];
    }
  }
}
