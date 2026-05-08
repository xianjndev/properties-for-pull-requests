<?php

namespace Homeful\Properties\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Properties\Models\Project;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word(),
            'name' => $this->faker->word(),
            'location' => $this->faker->city(),
            'address' => $this->faker->address(),
            'licenseNumber' => $this->faker->word(),
            'licenseDate' => Carbon::parse($this->faker->date()),
            'company_code' => $this->faker->word(),
            'appraised_lot_value' => $this->faker->numberBetween(2000.0,5000.0),
            'total_sold' => $this->faker->numberBetween(50, 100),
            'project_description' => $this->faker->word(),
            'company_name'=>$this->faker->word(),
            'company_address' => $this->faker->address(),
            'company_tin' => $this->faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{3}'),
            'pagibig_filing_site'=>$this->faker->address(),
            'exec_position'=>$this->faker->word(),
            'exec_signatory'=>$this->faker->word(),
            'exec_tin'=>$this->faker->regexify('[0-9]{3}-[0-9]{3}-[0-9]{3}'),
            'board_resolution_date'=>Carbon::parse($this->faker->date()),
            'filing_site' => $this->faker->word(),
        ];
    }
}
