<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $category_id)
 * @method static where(string $string, int $category_id)
 */
class Dictionary extends Model
{
    use HasFactory;

    protected $table = "dictionary";
}
