<?php declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Products extends Model {
  use HasFactory;
  use HasUuids;

  protected $table = 'products';
  protected $fillable = ['name', 'sku', 'stock', 'price', 'category_id'];

  public function category(): BelongsTo {
    return $this->belongsTo(Categories::class, 'category_id');
  }
}
