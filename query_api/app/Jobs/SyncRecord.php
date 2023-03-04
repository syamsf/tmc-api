<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MariaDB\Products as ProductsRepo;
use App\Helpers\Engine\Products as EngineProducts;
use App\Repositories\MariaDB\Categories as CategoriesRepo;
use App\Helpers\Engine\Categories as EngineCategories;

class SyncRecord implements ShouldQueue
{
  private array $createdData;
  private string $tableName;

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(array $createdData, string $tableName) {
    $this->createdData = $createdData;
    $this->tableName = $tableName;
  }

  public function handle(): void {
    try {
      if (!in_array($this->tableName, ['products', 'categories']))
        throw new \Exception("table name {$this->tableName} is not allowed");

      $id = $this->createdData['id'];

      if ($this->tableName == 'products') {
        $productRepo    = new ProductsRepo();
        $engineProducts = new EngineProducts($productRepo);
        $engineProducts->createDataSync($this->createdData);
      } else {
        $categoriesRepo = new CategoriesRepo();
        $engineCategories = new EngineCategories($categoriesRepo);
        $engineCategories->createDataSync($this->createdData);
      }

      echo " *** Success - Sync Record to '{$this->tableName}' with ID: {$id} ***" . PHP_EOL;
    } catch (\Exception $e) {
      echo " *** Failed - Sync Record to '{$this->tableName}' with ID: {$id} - {$e->getMessage()} ***" . PHP_EOL;
    }

    // Tells the framework to reboot after each successful execution
    // This things prevent memory leak caused by worker (daemon)
    // Reference:
    //  - https://divinglaravel.com/rationing-your-laravel-queue-workers-memory-and-cpu-consumption
    //  - https://stackoverflow.com/questions/30060526/laravel-artisan-cli-safely-stop-daemon-queue-workers
    app('queue.worker')->shouldQuit = 1;
  }
}
