<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->regexify('[A-Za-z0-9]{8}'),
            'api_key' => $this->faker->uuid(),
            'legacy_api_key' => $this->faker->regexify('[A-Za-z0-9]{32}')
        ];
    }
}
