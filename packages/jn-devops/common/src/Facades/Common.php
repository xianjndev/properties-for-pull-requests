<?php

namespace Homeful\Common\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\Common\Common
 */
class Common extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\Common\Common::class;
    }
}
