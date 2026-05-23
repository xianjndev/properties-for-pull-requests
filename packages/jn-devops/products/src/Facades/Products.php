<?php

namespace Homeful\Products\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\Products\Products
 */
class Products extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\Products\Products::class;
    }
}
