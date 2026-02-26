<?php

namespace Homeful\Common\Interfaces;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Carbon;
use Whitecube\Price\Price;
use Brick\Money\Money;

interface BorrowerInterface
{
    public function getContactId(): string;

    public function getBirthdate(): Carbon;

    public function getWages(): Money|float;

    public function getRegional(): bool;

    public function getMobile(): PhoneNumber;

    public function getSellerCommissionCode(): string;

    public function getGrossMonthlyIncome(): Price;
}
