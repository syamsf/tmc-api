<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Requests\Products\CreateValidation as CreateValidationProducts;
use App\Repositories\MariaDB\Products as ProductsRepo;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\Engine\Products as EngineProducts;
use App\Http\Controllers\Controller;

class Products extends Controller {
  public function create(CreateValidationProducts $request): array {
    try {
      $sku        = $request->input('sku');
      $name       = $request->input('name');
      $price      = $request->input('price');
      $stock      = $request->input('stock');
      $categoryId = $request->input('categoryId');

      $productsRepo   = new ProductsRepo();
      $engineProducts = new EngineProducts($productsRepo);
      $result = $engineProducts->createData([
        'sku'         => $sku,
        'name'        => $name,
        'price'       => $price,
        'stock'       => $stock,
        'category_id' => $categoryId
      ]);

      return ['data' => $result];
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();

      switch ($e->getCode()) {
        case 23000:
          $errorMessage = 'Category ID is not found';
          break;
        case 22001:
            $errorMessage = 'Data too long for Category ID';
        default:
          break;
      }

      throw new HttpResponseException(response()->json([
        'errors' => ['message' => $errorMessage]
      ], 400));
    }
  }
}
