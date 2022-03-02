<?php

namespace Database\Factories;

use App\Models\Update;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Update>
 */
class UpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $version = $this->faker->semver(false, false);
        return [
            'version' => $version,
            'critical' => $this->faker->boolean(),
            'public' => $this->faker->boolean(),
            'filename' => $version . '.' . $this->faker->fileExtension(),
        ];
    }

    /**
     * Define the model's state.
     *
     * @return Factory
     */
    public function public()
    {
        return $this->state(function () {
            return [
                'public' => true,
            ];
        });
    }
}
