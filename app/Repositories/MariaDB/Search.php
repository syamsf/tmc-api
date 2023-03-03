<?php declare(strict_types = 1);

namespace App\Repositories\MariaDB;

use Illuminate\Support\Facades\DB;

class Search {
  private string $tblProducts = 'products';
  private string $tblCategories = 'categories';

  public function searchProduct(array $searchKey, int $perPage = 10, int $page = 1): array {
    if (empty($searchKey))
      return [];

    if ($perPage <= 0 || $page <= 0)
      throw new \Exception("invalid perPage or page value, value should be greater than 0");

    $query = DB::table("{$this->tblProducts} as t1")
      ->join("{$this->tblCategories} as t2", "t1.category_id", "=", "t2.id")
      ->select([
        't1.id',
        't1.sku',
        't1.name',
        't1.price',
        't1.stock',
        't1.created_at',
        't1.updated_at',
        't2.id as category_id',
        't2.name as category_name'
      ]);

    $allowedColumn = ['sku', 'name', 'category_id', 'price'];
    $dataKey       = array_values(array_unique(array_keys($searchKey)));
    $intersectKey  = array_values(array_intersect($allowedColumn, $dataKey));

    foreach ($intersectKey as $item) {
      if (empty($searchKey[$item]))
        continue;

      // Search based on SKU and category_id
      // will use whereIn()
      if (in_array($item, ['sku', 'category_id'])) {
        $query->whereIn("t1.{$item}", $searchKey[$item]);
        continue;
      }

      // Search based on names
      // will use where() with LIKE %somevalue%
      if ($item == 'names') {
        foreach ($searchKey[$item] as $itemName) {
          $query->where("t1.{$item}", 'LIKE', "%{$itemName}%");
        }
        continue;
      }

      // Search based on price lower bound or upper bound
      if ($item == 'price') {
        $lowerBound = $searchKey[$item]['lower_bound'] ?? -1;
        $upperBound = $searchKey[$item]['upper_bound'] ?? -1;

        $lowerBoundValidation = $this->isPriceLowerBoundExist($lowerBound);
        $upperBoundValidation = $this->isPriceUpperBoundExist($upperBound);

        if ($lowerBoundValidation && $upperBoundValidation) {
          $query->whereBetween($item, [$lowerBound, $upperBound]);
          continue;
        }

        if ($lowerBoundValidation) {
          $query->where($item, '>=', $lowerBound);
          continue;
        }

        if ($upperBoundValidation) {
          $query->where($item, '<=', $upperBound);
          continue;
        }
      }
    }

    // Counter
    $allRecord = $query->count();

    // Pagination
    $skipValue = ($page - 1) * $perPage;
    $query->offset($skipValue)->limit($perPage);

    $result    = $query->get();
    $resultArr = json_decode(json_encode($result), true);

    $completeResult = [
      'data'   => $resultArr,
      'paging' => [
        'size'    => count($resultArr),
        'total'   => $allRecord,
        'current' => $page
      ]
    ];

    return $completeResult ?? [];
  }

  private function isPriceUpperBoundExist(int $upperBound = -1): bool {
    return $upperBound >= 0;
  }

  private function isPriceLowerBoundExist(int $lowerBound = -1): bool {
    return $lowerBound >= 0;
  }
}
