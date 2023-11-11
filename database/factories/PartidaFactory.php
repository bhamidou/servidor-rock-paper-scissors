<?php

namespace Database\Factories;

use App\Models\Ronda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partida>
 */
class PartidaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_ronda' => Ronda::query()->inRandomOrder()->first()->id,
            'tirada_user_1' => $this->faker->randomElement(["rock","paper","scissors"]),
            'tirada_user_2' => $this->faker->randomElement(["rock","paper","scissors"]),
            'ganador' =>  Ronda::query()->inRandomOrder()->first()->ganador
        ];
    }

}
