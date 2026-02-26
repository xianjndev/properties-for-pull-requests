<?php

namespace Homeful\Common\Interfaces;

use phpDocumentor\Reflection\Types\Integer;
use Whitecube\Price\Price;

interface ProductInterface
{
    public function getSKU(): string;

    public function getProcessingFee(): Price;

    public function getTotalContractPrice(): Price;

    public function getAppraisedValue(): Price;

    public function getPercentDownPayment(): float;

    public function getDownPaymentTerm(): int;

    public function getPercentMiscellaneousFees(): float;
}
