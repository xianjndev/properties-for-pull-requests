<?php

namespace Homeful\Common\Interfaces;

use Whitecube\Price\Price;

/**
 * @deprecated
 */
interface PropertyInterface
{
    public function getSKU(): string;

    public function getProcessingFee(): Price;

    public function getTotalContractPrice(): Price;

    public function getAppraisedValue(): Price;
}
