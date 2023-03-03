<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repositories\MariaDB\Search as SearchRepo;
use App\Helpers\Engine\Search as SearchEngine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class Query extends Controller {
  public function search(Request $request): array {
    try {
      // name
      $isNameExist = $request->has('name');
      $names = $isNameExist ? $request->query('name') : [];

      // sku
      $isSkuExist = $request->has('sku');
      $sku = $isSkuExist ? $request->query('sku') : [];

      // category
      $category = $request->query('category_id') ?? [];

      // price
      $priceLowerBound = $request->query('price_start') ?? -1;
      $priceLowerBound = (int)$priceLowerBound;
      $priceUpperBound = $request->query('price_end') ?? -1;
      $priceUpperBound = (int)$priceUpperBound;

      $perPage = $request->query('per_page') ?? 10;
      $perPage = (int)$perPage;
      $page = $request->query('page') ?? 1;
      $page = (int)$page;

      $searchKey = [
        'sku'         => $sku,
        'name'        => $names,
        'category_id' => $category,
        'price' => [
          'lower_bound' => $priceLowerBound,
          'upper_bound' => $priceUpperBound
        ]
      ];

      $searchRepo = new SearchRepo();
      $searchEngine = new SearchEngine($searchRepo);
      $result = $searchEngine->searchProduct($searchKey, $perPage, $page);

      return $result;
    } catch (\Exception $e) {
      throw new HttpResponseException(response()->json([
        'errors' => ['message' => $e->getMessage()]
      ], 400));
    }
  }
}
