<?php declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model {
  use HasFactory;
  use HasUuids;

  protected $table = 'categories';
  protected $fillable = ['name'];
}
