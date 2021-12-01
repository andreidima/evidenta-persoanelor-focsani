<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nume' => $this->faker->name(),
            'email' => $this->faker->email(),
            'cnp' => rand(1000101111111, 2991231509999)
        ];
    }
}
