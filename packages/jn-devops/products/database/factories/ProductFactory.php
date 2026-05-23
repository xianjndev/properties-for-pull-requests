<?php

namespace Homeful\Products\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Products\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => $this->faker->word(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'category' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'price' => (float) $this->faker->numberBetween(850000, 2500000),
            'market_segment' => $this->faker->word(),
            'location' => $this->faker->word(),
            'destinations' => $this->faker->paragraph(),
            'directions' => $this->faker->paragraph(),
            'amenities' => $this->faker->paragraph(),
            'facade_url' => $this->faker->imageUrl(),
            // Additional columns
            'project_location' => $this->faker->city(),
            'project_code' => $this->faker->word(),
            'property_name' => $this->faker->word(),
            'phase' => $this->faker->word(),
            'block' => $this->faker->word(),
            'lot' => $this->faker->word(),
            'lot_area' => (float) $this->faker->numberBetween(100, 1000),
            'floor_area' => (float) $this->faker->numberBetween(50, 500),
            'project_address' => $this->faker->address(),
            'property_type' => $this->faker->word(),
            'unit_type' => $this->faker->word(),
            // Interface fields
            'appraised_value' => (float) $this->faker->numberBetween(850000, 2500000),
            'percent_down_payment' => (float) $this->faker->numberBetween(5, 10)/100,
            'down_payment_term' => $this->faker->numberBetween(6, 12),
            'percent_miscellaneous_fees' => (float) $this->faker->numberBetween(5, 10)/100,
            'digital_assets' => (string) $this->faker->paragraph(),
            'phased_out' => $this->faker->boolean(),
        ];
    }
}
