<?php declare(strict_types = 1);

namespace App\Repositories\MariaDB;

use App\Models\User as ModelUser;

class User {
  private string $appName = 'tmc-api';

  public function create(string $email, string $name, string $password): string {
    if (empty($email) || empty($name) || empty($password))
      throw new \Exception("email and name and password is required");

    $result = ModelUser::create([
      'email'    => $email,
      'name'     => $name,
      'password' => $password
    ]);
    $token = $result->createToken($this->appName)->plainTextToken;

    return $token;
  }
}
