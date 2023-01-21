<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'price' => fake()->randomDigit(),
        ];
    }
}
