<?php

namespace Homeful\Common\Classes;

use Whitecube\Price\Price;

class DeductibleFeeFromPayment extends AmountCollectionItem
{
    public function __construct(string $name, Price|float $amount, string $tag)
    {
        parent::__construct(name: $name, amount: $amount, deductible: true, tag: $tag);
    }
}
