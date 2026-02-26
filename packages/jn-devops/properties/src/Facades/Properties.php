<?php

namespace Homeful\Properties\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\Properties\Properties
 */
class Properties extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\Properties\Properties::class;
    }
}
