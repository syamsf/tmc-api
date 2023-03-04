<?php

namespace Tests\Unit\app\Http\Controllers\API;

use App\Requests\Categories\CreateValidation as CategoriesCreateValidation;
use Faker\Factory;
use Tests\TestCase;

class CategoriesCreationTest extends TestCase {
  private array $rules;
  private $validator;

  public function setUp(): void {
    parent::setUp();
    $this->rules = (new CategoriesCreateValidation())->rules();
    $this->validator = app()->get('validator');
  }

  public function validationProvider(): array {
    $faker = Factory::create(Factory::DEFAULT_LOCALE);

    return [
      'request_should_fail_when_no_name_is_provided' => [
        'pass'   => false,
        'data'   => [
          'name' => ''
        ]
      ],
      'request_should_work_when_name_is_provided' => [
        'pass'   => true,
        'data'   => [
          'name' => 'test categories'
        ]
      ],
      'request_should_fail_when_name_is_greater_than_255' => [
        'pass'   => false,
        'data'   => [
          'name' => $faker->words(256)
        ]
      ],
    ];
  }

  /**
   * @test
   * @dataProvider validationProvider
   */
  public function validation_result(bool $shouldPass, array $mockedRequestData): void {
    $this->assertEquals($shouldPass, $this->validate($mockedRequestData));
  }

  private function validate(array $mockedRequestData): bool {
    return $this->validator
      ->make($mockedRequestData, $this->rules)
      ->passes();
  }
}
