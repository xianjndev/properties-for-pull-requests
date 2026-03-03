<?php

namespace Homeful\Properties\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Properties\Models\Property;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word(),
            'name' => $this->faker->word(),
            'type' => $this->faker->word(),
            'cluster' => (string) $this->faker->numberBetween(1,10),
            'phase' => $this->faker->word(),
            'block' => $this->faker->word(),
            'lot' => $this->faker->word(),
            'building' => $this->faker->word(),
            'floor_area' => $this->faker->numberBetween(25,40) * 1.0,
            'lot_area' => $this->faker->numberBetween(25,40) * 1.0,
            'unit_type' => $this->faker->word(),
            'unit_type_interior' => $this->faker->word(),
            'house_color' => $this->faker->word(),
            'roof_style' => $this->faker->word(),
            'end_unit' => $this->faker->boolean(),
            'veranda' => $this->faker->boolean(),
            'balcony' => $this->faker->boolean(),
            'firewall' => $this->faker->boolean(),
            'eaves' => $this->faker->boolean(),
            'bedrooms' => $this->faker->numberBetween(1,2),
            'toilets_and_bathrooms' => $this->faker->numberBetween(1,2),
            'parking_slots' => $this->faker->numberBetween(1,2),
            'carports' => $this->faker->numberBetween(1,2),
            'project_code' => $this->faker->word(),
            'project_location' => $this->faker->sentence(),
            'project_address' => $this->faker->sentence(),
            'sku' => $this->faker->word(),
            'status' => $this->faker->word(),
            'tcp' => $this->faker->numberBetween(750000,850000) * 1.0,
        ];
    }
}
