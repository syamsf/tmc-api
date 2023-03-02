<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  private string $table = 'products';
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->string('sku')->unique();
      $table->string('name');
      $table->bigInteger('price');
      $table->integer('stock');
      $table->foreignUuid('category_id')
        ->references('id')
        ->on('categories')
        ->restrictOnDelete()
        ->cascadeOnUpdate();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::drop($this->table);
  }
};
