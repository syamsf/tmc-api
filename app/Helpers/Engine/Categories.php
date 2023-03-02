<?php declare(strict_types = 1);

namespace App\Helpers\Engine;

use App\Repositories\Interface\Categories as InterfaceCategories;

class Categories {
  public function __construct(private InterfaceCategories $categoriesRepo) {}

  public function createData(string $name): array {
    $result = $this->categoriesRepo->insert($name);
    $result['createdAt'] = $result['created_at'];

    unset($result['created_at']);

    return $result;
  }
}
