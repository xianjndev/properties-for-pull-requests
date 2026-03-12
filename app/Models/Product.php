<?php

namespace App\Models;

use Homeful\Common\Casts\PriceCast;
use Homeful\Products\Models\Product as ModelsProduct;
use Illuminate\Database\Eloquent\Model;

class Product extends ModelsProduct
{
    protected $fillable = [
        'sku',
        'name',
        'brand',
        'category',
        'description',
        'price',
        'digital_assets',
        'phased_out',
        'hdmf',
        'bank',
        'preferred_option',
    ];

    protected $casts = [
        'price' => PriceCast::class,
        'bank' => 'boolean',
        'hdmf' => 'boolean',
    ];

}
