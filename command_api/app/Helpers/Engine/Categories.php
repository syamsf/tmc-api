<?php declare(strict_types = 1);

namespace App\Helpers\Engine;

use App\Repositories\Interface\Categories as InterfaceCategories;
use App\Jobs\SyncRecord as SyncRecordJob;
use Carbon\Carbon;

class Categories {
  public function __construct(private InterfaceCategories $categoriesRepo) {}

  public function createData(string $name): array {
    $result = $this->categoriesRepo->insert($name);

    $this->dispatchCreatedData($result);

    $createdAt = Carbon::parse($result['created_at'])->setTimezone('UTC')->timestamp;
    $result['createdAt'] = $createdAt;

    unset($result['created_at']);

    return $result;
  }

  public function dispatchCreatedData(array $data): void {
    $reformattedData = [
      'id'         => $data['id'],
      'name'       => $data['name'],
      'created_at' => Carbon::parse($data['created_at'])->format('Y-m-d h:i:s')
    ];

    SyncRecordJob::dispatch($reformattedData, 'categories')->onQueue('sync-record');
  }

  public function createDataSync(array $data): void {
    $this->categoriesRepo->insertSync($data);
  }
}
