<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $type)
 */
class ProductTag extends Model
{
    use HasFactory;

    protected $table = "product_tag";
}
