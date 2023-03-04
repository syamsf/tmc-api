<?php declare(strict_types = 1);

namespace App\Helpers\Engine;

use App\Repositories\MariaDB\Search as SearchRepo;
use Carbon\Carbon;

class Search {
  public function __construct(private SearchRepo $searchRepo) {}

  public function searchProduct(
    array $searchKey = [],
    int $perPage = 10,
    $page = 1
  ): array {
    $defaultResponse = [
      'data'   => [],
      'paging' => [
        'size'    => 0,
        'total'   => 0,
        'current' => $page
      ]
    ];

    $result = $this->searchRepo->searchProduct($searchKey, $perPage, $page);

    if (empty($result))
      return $defaultResponse;

    $reformattedResult = $this->reformatResponse($result);
    return $reformattedResult;
  }

  private function reformatResponse(array $data): array {
    $newItem = [];

    foreach ($data['data'] as $item) {
      $item['category'] = [
        'id'   => $item['category_id'],
        'name' => $item['category_name']
      ];

      $createdAt = Carbon::parse($item['created_at'])->setTimezone('UTC')->timestamp;
      $item['createdAt'] = $createdAt;

      unset($item['created_at']);
      unset($item['updated_at']);
      unset($item['category_id']);
      unset($item['category_name']);

      $newItem[] = $item;
    }

    $reformattedResult = [
      'data'   => $newItem,
      'paging' => $data['paging']
    ];

    return $reformattedResult;
  }
}
