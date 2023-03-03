<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Categories;
use App\Http\Controllers\API\Products;
use App\Http\Controllers\API\Query;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['json.response']], function () {
  // Grouping based on v1
  Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'categories'], function () {
      Route::post('/', [Categories::class, 'create']);
    });

    Route::group(['prefix' => 'products'], function () {
      Route::post('/', [Products::class, 'create']);
    });

    Route::group(['prefix' => 'token'], function() {
      Route::post('/');
    });

    Route::group(['prefix' => 'search'], function() {
      Route::get('/', [Query::class, 'search']);
    });
  });
});
