<?php declare(strict_types = 1);

namespace App\Repositories\Interface;

interface Categories {
  public function findById(string $uuid): array;
  public function findByName(string $name): array;
  public function fetchAll(): array;
  public function insert(string $name): array|bool;
  public function update(array $searchColumn, string $name): array|bool;
  public function delete(array $searchColumn): array|bool;
}
