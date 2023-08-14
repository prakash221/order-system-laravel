<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'product_image',
        'price',
        'description',
        'is_discontinued',
    ];
}
