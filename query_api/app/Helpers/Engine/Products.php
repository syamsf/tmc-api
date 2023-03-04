<?php declare(strict_types = 1);

namespace App\Helpers\Engine;

use App\Repositories\Interface\Products as InterfaceProducts;
use App\Jobs\SyncRecord as SyncRecordJob;
use Carbon\Carbon;

class Products {
  public function __construct(private InterfaceProducts $productsRepo) {}

  public function createData(array $data): array {
    $result = $this->productsRepo->insert($data)["0"];

    $this->dispatchCreatedData($result);

    $createdAt = Carbon::parse($result['created_at'])->setTimezone('UTC')->timestamp;
    $result['createdAt'] = $createdAt;

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

  public function dispatchCreatedData(array $data): void {
    $reformattedData = [
      'id'          => $data['id'],
      'sku'         => $data['sku'],
      'name'        => $data['name'],
      'price'       => $data['price'],
      'stock'       => $data['stock'],
      'created_at'  => $data['created_at'],
      'category_id' => $data['category_id']
    ];

    SyncRecordJob::dispatch($reformattedData, 'products')->onQueue('sync-record');
  }

  public function createDataSync(array $data): void {
    $this->productsRepo->insertSync($data);
  }
}
