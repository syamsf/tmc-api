<?php declare(strict_types = 1);

namespace App\Repositories\MariaDB;

use App\Repositories\Interface\Products as InterfaceProducts;
use App\Models\Products as ModelProducts;

class Products implements InterfaceProducts {
  public function findById(string $uuid): array {
    return [];
  }

  public function findByName(string $name): array {
    return [];
  }

  public function fetchAll(): array {
    return [];
  }

  public function insert(array $data): array|bool {
    $this->validateInsertData($data);

    $result = ModelProducts::create($data);
    $resultWithCategory = ModelProducts::where('id', $result->id)->with('category')->get()->toArray();

    return $resultWithCategory;
  }

  public function insertSync(array $data): void {
    ModelProducts::create($data);
  }

  public function update(array $searchColumn, string $name): array|bool {
    // TODO: need to implement the logic
    return false;
  }

  public function delete(array $searchColumn): array|bool{
    // TODO: need to implement the logic
    return false;
  }

  private function validateInsertData(array $data): void {
    if (empty($data))
      throw new \Exception("data is required to insert record", 0);

    $requiredField = ['sku', 'name', 'price', 'stock', 'category_id'];
    foreach ($requiredField as $item) {
      if (empty($data[$item] ?? ''))
        throw new \Exception("field {$item} is required", 0);
    }
  }

}
