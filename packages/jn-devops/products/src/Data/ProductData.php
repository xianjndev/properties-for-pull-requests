<?php

namespace Homeful\Products\Data;

use Homeful\Products\Models\Product;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public string $sku,
        public string $name,
        public string $brand,
        public string $category,
        public string $description,
        public float $price,
        public string $market_segment,
        public string $location,
        public string $destinations,
        public string $directions,
        public string $amenities,
        public string $facade_url,
        // Additional fields
        public string $project_location,
        public string $project_code,
        public string $property_name,
        public string $phase,
        public string $block,
        public string $lot,
        public ?float $lot_area,
        public ?float $floor_area,
        public string $project_address,
        public string $property_type,
        public string $house_type,
        public string $unit_type,
        public ?string $digital_assets,
        public string $status_code,
        public string $key_location,
        public ?bool $phased_out,
        public ?bool $bank,
        public ?bool $hdmf,
        public ?string $preferred_option,
        // Interface fields
        public float $appraised_value,
        public float $percent_down_payment,
        public int $down_payment_term,
        public float $percent_miscellaneous_fees,
        public float $percent_gross_monthly_income,
        public int $max_age,
        public float $balance_payment_interest_rate,
        public float $mortgage_redemption_insurance_fee,
        public float $income_requirement_multiplier,
        public int $maximum_paying_age,
        public int $balance_payment_term,
        public float $processing_fee
    ) {}

    public static function fromModel(Product $product): ProductData
    {
        return new self(
            sku: $product->sku,
            name: $product->name,
            brand: $product->brand,
            category: $product->category,
            description: $product->description,
            price: $product->price->inclusive()->getAmount()->toFloat(),
            market_segment: $product->market_segment,
            location: $product->location,
            destinations: $product->destinations,
            directions: $product->directions,
            amenities: $product->amenities,
            facade_url: $product->facade_url,
            // Additional fields
            project_location: $product->project_location,
            project_code: $product->project_code,
            property_name: $product->property_name,
            phase: $product->phase,
            block: $product->block,
            lot: $product->lot,
            lot_area: $product->lot_area,
            floor_area: $product->floor_area,
            project_address: $product->project_address,
            property_type: $product->property_type,
            house_type: $product->house_type,
            unit_type: $product->unit_type,
            digital_assets: $product->digital_assets,
            status_code: $product->status_code,
            key_location: $product->key_location,
            phased_out: $product->phased_out,
            bank: $product->bank,
            hdmf: $product->hdmf,
            preferred_option: $product->preferred_option,
            // Interface fields
            appraised_value: $product->appraised_value->inclusive()->getAmount()->toFloat(),
            percent_down_payment: $product->percent_down_payment,
            down_payment_term: $product->down_payment_term,
            percent_miscellaneous_fees: $product->percent_miscellaneous_fees,
            percent_gross_monthly_income: $product->percent_gross_monthly_income,
            max_age: $product->max_age,
            balance_payment_interest_rate: $product->balance_payment_interest_rate,
            mortgage_redemption_insurance_fee: $product->mortgage_redemption_insurance_fee,
            income_requirement_multiplier: $product->income_requirement_multiplier,
            maximum_paying_age: $product->maximum_paying_age,
            balance_payment_term: $product->balance_payment_term,
            processing_fee: $product->processing_fee
        );
    }
}
