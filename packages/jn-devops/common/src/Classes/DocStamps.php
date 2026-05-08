<?php

namespace Homeful\Common\Classes;

use Whitecube\Price\Price;
use Brick\Money\Money;

class DocStamps
{
    protected Price $value;

    public function __construct(Price|Money|int $value)
    {
        $this->value = ($value instanceof Price)
            ? $value
            : new Price(($value instanceof Money) ? $value : Money::of($value, 'PHP'));
    }

    public function getValue(): Price
    {
        return $this->value;
    }

    public function getPrice(): Price
    {
        $value = $this->getValue()->inclusive()->getAmount()->toInt();
        $amount = match(true) {
            $value <= 100000 => 0,
            $value <= 300000 => 20,
            $value <= 500000 => 50,
            $value <= 750000 => 100,
            $value <= 1000000 => 150,
            default => 200
        };

        return new Price(Money::of($amount, 'PHP'));
    }
}
