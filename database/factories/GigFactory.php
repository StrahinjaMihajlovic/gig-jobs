<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::random(100),
            'description' => Str::random(500),
            'timestamp_start' => $this->faker->dateTimeBetween('this_week', '+5 days'),
            'timestamp_end' => $this->faker->dateTimeBetween('+5 days', '+15 days'),
            'number_of_positions' => $this->faker->randomNumber(2),
            'pay_per_hour' => $this->faker->randomFloat(2, 0, 20),
            'status' => $this->faker->numberBetween(0, 1),
        ];
    }
}
