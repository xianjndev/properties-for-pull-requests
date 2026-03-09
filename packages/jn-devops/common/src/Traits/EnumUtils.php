<?php

namespace Homeful\Common\Traits;

use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rule;

trait EnumUtils
{
    static function random(): self {
        $enums = self::cases(); return $enums[array_rand($enums)];
    }

    static function toArray($column_key = 'value'): array {
       return array_column(array: self::cases(), column_key: $column_key);
    }

    static function rule(): In
    {
        return Rule::in(self::toArray());
    }
}
