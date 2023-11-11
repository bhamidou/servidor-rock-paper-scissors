<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ronda>
 */
class RondaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        return [
            'id_user_1' => $user1,
            'id_user_2' => $user2,
            'status' => rand(0,1),
            'ganador' =>  $this->faker->randomElement([$user1, $user2])
        ];
    }

    public function createUser(){
        return Usuario::query()->inRandomOrder()->first()->id;
    }
}
