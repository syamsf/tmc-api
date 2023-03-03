<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Repositories\MariaDB\User as UserRepo;
use App\Http\Controllers\Controller;
use App\Requests\User\CreateValidation;
use Illuminate\Http\Request;


class Token extends Controller {
  public function createToken(CreateValidation $request): array {
    try {
      $email    = $request->input('email');
      $name     = $request->input('name');
      $password = $request->input('password');

      $userRepo = new UserRepo();
      $token = $userRepo->create($email, $name, $password);

      return [
        'data' => ['token' => $token]
      ];
    } catch (\Exception $e) {
      throw new HttpResponseException(response()->json([
        'errors' => ['message' => $e->getMessage()]
      ], 400));
    }
  }
}
