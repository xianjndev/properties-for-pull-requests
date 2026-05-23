<?php

namespace Homeful\Products\Traits;

use Homeful\Products\Models\Product;
use Whitecube\Price\Price;
use Brick\Money\Money;

trait HasAdditionalAttributes
{
    const MARKET_SEGMENT_FIELD = 'market_segment';
    const LOCATION_FIELD = 'location';
    const DESTINATIONS_FIELD = 'destinations';
    const DIRECTIONS_FIELD = 'directions';
    const AMENITIES_FIELD = 'amenities';
    const FACADE_URL_FIELD = 'facade_url';
    // New constants for additional property fields
    const PROJECT_LOCATION_FIELD = 'project_location';
    const PROJECT_CODE_FIELD = 'project_code';
    const PROPERTY_NAME_FIELD = 'property_name';
    const PHASE_FIELD = 'phase';
    const BLOCK_FIELD = 'block';
    const LOT_FIELD = 'lot';
    const LOT_AREA_FIELD = 'lot_area';
    const FLOOR_AREA_FIELD = 'floor_area';
    const PROJECT_ADDRESS_FIELD = 'project_address';
    const PROPERTY_TYPE_FIELD = 'property_type';
    const UNIT_TYPE_FIELD = 'unit_type';
    const APPRAISED_VALUE = 'appraised_value';
    const PERCENT_DOWN_PAYMENT = 'percent_down_payment';
    const DOWN_PAYMENT_TERM = 'down_payment_term';
    const PERCENT_MISCELLANEOUS_FEES = 'percent_miscellaneous_fees';
    const STATUS_CODE = 'status_code';
    const KEY_LOCATION = 'key_location';
    const DIGITAL_ASSETS = 'digital_assets';
    const PERCENT_GROSS_MONTHLY_INCOME = 'percent_gross_monthly_income';
    const MAX_AGE = 'max_age';
    const BALANCE_PAYMENT_INTEREST_RATE = 'balance_payment_interest_rate';
    const MORTGAGE_REDEMPTION_INSURANCE_FEE = 'mortgage_redemption_insurance_fee';
    const INCOME_REQUIREMENT_MULTIPLIER = 'income_requirement_multiplier';
    const MAXIMUM_PAYING_AGE = 'maximum_paying_age';
    const BALANCE_PAYMENT_TERM = 'balance_payment_term';
    const PROCESSING_FEE = 'processing_fee';
    const HOUSE_TYPE = 'house_type';
    const PHASED_OUT = 'phased_out';

    public function initializeHasAdditionalAttributes(): void
    {
        $this->mergeFillable([
            'market_segment',
            'location',
            'destinations',
            'project_location',
            'project_code',
            'property_name',
            'phase',
            'block',
            'lot',
            'lot_area',
            'floor_area',
            'project_address',
            'property_type',
            'unit_type',
            'percent_down_payment',
            'down_payment_term',
            'percent_miscellaneous_fees',
            'mortgage_redemption_insurance_fee',
            'income_requirement_multiplier',
            'maximum_paying_age',
            'interest_rate',
            'max_age',
            'percent_gross_monthly_income',
            'balance_payment_interest_rate',
            'balance_payment_term',

        ]);
    }

    // Setters and Getters for each field

    public function setMarketSegmentAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::MARKET_SEGMENT_FIELD, $value);
        return $this;
    }

    public function getMarketSegmentAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::MARKET_SEGMENT_FIELD) ?? '';
    }

    public function setLocationAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::LOCATION_FIELD, $value);
        return $this;
    }

    public function getLocationAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::LOCATION_FIELD) ?? '';
    }

    public function setDestinationsAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::DESTINATIONS_FIELD, $value);
        return $this;
    }

    public function getDestinationsAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::DESTINATIONS_FIELD) ?? '';
    }

    public function setDirectionsAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::DIRECTIONS_FIELD, $value);
        return $this;
    }

    public function getDirectionsAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::DIRECTIONS_FIELD) ?? '';
    }

    public function setAmenitiesAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::AMENITIES_FIELD, $value);
        return $this;
    }

    public function getAmenitiesAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::AMENITIES_FIELD) ?? '';
    }

    public function setFacadeUrlAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::FACADE_URL_FIELD, $value);
        return $this;
    }

    public function getFacadeUrlAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::FACADE_URL_FIELD) ?? '';
    }

    // Methods for additional fields

    public function setProjectLocationAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROJECT_LOCATION_FIELD, $value);
        return $this;
    }

    public function getProjectLocationAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PROJECT_LOCATION_FIELD) ?? '';
    }

    public function setProjectCodeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROJECT_CODE_FIELD, $value);
        return $this;
    }

    public function getProjectCodeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PROJECT_CODE_FIELD) ?? '';
    }

    public function setPropertyNameAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROPERTY_NAME_FIELD, $value);
        return $this;
    }

    public function getPropertyNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PROPERTY_NAME_FIELD) ?? '';
    }

    public function setPhaseAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PHASE_FIELD, $value);
        return $this;
    }

    public function getPhaseAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PHASE_FIELD) ?? '';
    }

    public function setBlockAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::BLOCK_FIELD, $value);
        return $this;
    }

    public function getBlockAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::BLOCK_FIELD) ?? '';
    }

    public function setLotAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::LOT_FIELD, $value);
        return $this;
    }

    public function getLotAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::LOT_FIELD) ?? '';
    }

    public function setLotAreaAttribute(?float $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::LOT_AREA_FIELD, $value);
        return $this;
    }

    public function getLotAreaAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::LOT_AREA_FIELD) ?? 0;
    }

    public function setFloorAreaAttribute(?float $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::FLOOR_AREA_FIELD, $value);
        return $this;
    }

    public function getFloorAreaAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::FLOOR_AREA_FIELD) ?? 0;
    }

    public function setProjectAddressAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROJECT_ADDRESS_FIELD, $value);
        return $this;
    }

    public function getProjectAddressAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PROJECT_ADDRESS_FIELD) ?? '';
    }

    public function setPropertyTypeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROPERTY_TYPE_FIELD, $value);
        return $this;
    }

    public function getPropertyTypeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::PROPERTY_TYPE_FIELD) ?? '';
    }
    public function setHouseTypeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::HOUSE_TYPE, $value);
        return $this;
    }

    public function getHouseTypeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::HOUSE_TYPE) ?? '';
    }

    public function setUnitTypeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::UNIT_TYPE_FIELD, $value);
        return $this;
    }

    public function getUnitTypeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::UNIT_TYPE_FIELD) ?? '';
    }

    public function setAppraisedValueAttribute(Price|float $value): self
    {
        $value  = $value instanceof Price ? $value->inclusive()->getAmount()->toFloat() : $value;

        $this->getAttribute('meta')->set(Product::APPRAISED_VALUE, $value);

        return $this;
    }

    public function getAppraisedValueAttribute(): Price
    {
        $value = $this->getAttribute('meta')->get(Product::APPRAISED_VALUE);

        return $value ? new Price(Money::of($value, 'PHP')) :  $this->price;
    }

    public function setPercentDownPaymentAttribute(float $value): self
    {
        $this->getAttribute('meta')->set(Product::PERCENT_DOWN_PAYMENT, $value);

        return $this;
    }

    public function getPercentDownPaymentAttribute(): float
    {
        return $this->getAttribute('meta')->get(Product::PERCENT_DOWN_PAYMENT) ?? config('products.default.percent_dp');
    }

    public function setDownPaymentTermAttribute(int $value): self
    {
        $this->getAttribute('meta')->set(Product::DOWN_PAYMENT_TERM, $value);

        return $this;
    }

    public function getDownPaymentTermAttribute(): int
    {
        return $this->getAttribute('meta')->get(Product::DOWN_PAYMENT_TERM) ?? config('products.default.dp_term');
    }

    public function setPercentMiscellaneousFeesAttribute(float $value): self
    {
        $this->getAttribute('meta')->set(Product::PERCENT_MISCELLANEOUS_FEES, $value);

        return $this;
    }

    public function getPercentMiscellaneousFeesAttribute(): float
    {
        return $this->getAttribute('meta')->get(Product::PERCENT_MISCELLANEOUS_FEES) ?? config('products.default.percent_mf');
    }

    public function setStatusCodeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::STATUS_CODE, $value);
        return $this;
    }

    public function getStatusCodeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::STATUS_CODE) ?? '';
    }

    public function setKeyLocationAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::KEY_LOCATION, $value);
        return $this;
    }

    public function getKeyLocationAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::KEY_LOCATION) ?? '';
    }

    public function setDigitalAssetsAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::DIGITAL_ASSETS, $value);
        return $this;
    }

    public function getDigitalAssetsAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Product::DIGITAL_ASSETS) ?? '';
    }

    public function setPercentGrossMonthlyIncomeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PERCENT_GROSS_MONTHLY_INCOME, $value);
        return $this;
    }

    public function getPercentGrossMonthlyIncomeAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::PERCENT_GROSS_MONTHLY_INCOME) ?? 0.0;
    }

    public function setMaxAgeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::MAX_AGE, $value);
        return $this;
    }

    public function getMaxAgeAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Product::MAX_AGE) ?? 0;
    }

    public function setBalancePaymentInterestRateAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::BALANCE_PAYMENT_INTEREST_RATE, $value);
        return $this;
    }

    public function getBalancePaymentInterestRateAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::BALANCE_PAYMENT_INTEREST_RATE) ?? 0.0;
    }

    public function setMortgageRedemptionInsuranceFeeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::MORTGAGE_REDEMPTION_INSURANCE_FEE, $value);
        return $this;
    }

    public function getMortgageRedemptionInsuranceFeeAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::MORTGAGE_REDEMPTION_INSURANCE_FEE) ?? 0.0;
    }

    public function setIncomeRequirementMultiplierAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::INCOME_REQUIREMENT_MULTIPLIER, $value);
        return $this;
    }

    public function getIncomeRequirementMultiplierAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::INCOME_REQUIREMENT_MULTIPLIER) ?? 0.0;
    }

    public function setMaximumPayingAgeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::MAXIMUM_PAYING_AGE, $value);
        return $this;
    }

    public function getMaximumPayingAgeAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Product::MAXIMUM_PAYING_AGE) ?? 0;
    }

    public function setBalancePaymentTermAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::BALANCE_PAYMENT_TERM, $value);
        return $this;
    }

    public function getBalancePaymentTermAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Product::BALANCE_PAYMENT_TERM) ?? 0;
    }
    public function setProcessingFeeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }

        $this->getAttribute('meta')->set(Product::PROCESSING_FEE, $value);
        return $this;
    }

    public function getProcessingFeeAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Product::PROCESSING_FEE) ?? 0.0;
    }
    
    public function setPhasedOutAttribute(?bool $value): self
    {
        $this->getAttribute('meta')->set(Product::PHASED_OUT, $value);
        return $this;
    }

    public function getPhasedOutAttribute(): bool
    {
        return $this->getAttribute('meta')->get(Product::PHASED_OUT) ?? false;
    }
}
