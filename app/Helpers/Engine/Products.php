<?php declare(strict_types = 1);

namespace App\Helpers\Engine;

use App\Repositories\Interface\Products as InterfaceProducts;

class Products {
  public function __construct(private InterfaceProducts $productsRepo) {}

  public function createData(array $data): array {
    $result = $this->productsRepo->insert($data)["0"];

    $result['createdAt'] = $result['created_at'];

    $notNeededColumn = [
      'created_at',
      'updated_at',
      'category_id',
      'category' => ['created_at', 'updated_at']
    ];

    foreach ($notNeededColumn as $key => $value) {
      if ($key == 'category') {
        foreach ($notNeededColumn[$key] as $itemCategory) {
          unset($result[$key][$itemCategory]);
        }

        continue;
      }

      unset($result[$value]);
    }

    return $result;
  }
}
