<?php declare(strict_types = 1);

namespace App\Repositories\MariaDB;

use App\Repositories\Interface\Categories as InterfaceCategories;
use App\Models\Categories as ModelCategories;

class Categories implements InterfaceCategories {
  public function findById(string $uuid): array {
    return [];
  }

  public function findByName(string $name): array {
    return [];
  }

  public function fetchAll(): array {
    return [];
  }

  public function insert(string $name): array|bool {
    if (empty($name))
      throw new \Exception("name is required to insert record", 0);

    $result    = ModelCategories::create(['name' => $name]);

    $formattedResult = [
      'id'         => $result->id,
      'name'       => $result->name,
      'created_at' => $result->created_at
    ];

    return $formattedResult;
  }

  public function insertSync(array $data): void {
    ModelCategories::create($data);
  }

  public function update(array $searchColumn, string $name): array|bool {
    // TODO: need to implement the logic
    return false;
  }

  public function delete(array $searchColumn): array|bool{
    // TODO: need to implement the logic
    return false;
  }

}
