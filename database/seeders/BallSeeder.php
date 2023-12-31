<?php

namespace Database\Seeders;
use App\Models\Ball;

use Illuminate\Database\Seeder;

class BallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ball::create([
            "name" => "House Ball",
            "weight" => "10",
            "color" => "#e5013b",
            "type" => "DEFAULT",
            "user_id" => 1
        ]);

        Ball::create([
            "name" => "Motiv Ripcord",
            "weight" => "14",
            "color" => "#6d5c9c",
            "type" => "CUSTOM",
            "user_id" => 1
        ]);

        Ball::create([
            "name" => "Blue Hammer",
            "weight" => "15",
            "color" => "#20b3de",
            "type" => "CUSTOM",
            "user_id" => 1
        ]);
    }
}
