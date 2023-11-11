<?php

namespace Database\Seeders;

use App\Models\Ronda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RondaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ronda::factory(10)->create();
    }
}
