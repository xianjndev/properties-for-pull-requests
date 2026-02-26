<?php

namespace Homeful\Common\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;

trait HasPackageFactory
{
    use HasFactory;

    protected static function newFactory()
    {
        $path = str(get_called_class())->explode('\\');
        $package = $path[0];
        $domain = $path[1];
        $model = $path->last();

        return app(__(':package\:domain\Database\Factories\:modelFactory', compact('package', 'domain', 'model')))->new();
    }
}
