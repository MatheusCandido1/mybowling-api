<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Game::create([
            "game_date" => new Carbon('2023-10-18'),
            "total_score" => 225,
            "user_id" => 1,
            "location_id" => 1,
            "ball_id" => 1,
            "status" => "COMPLETED"
        ]);

        Game::create([
            "game_date" => new Carbon('2023-10-20'),
            "total_score" => 129,
            "user_id" => 1,
            "location_id" => 1,
            "ball_id" => 2,
            "status" => "COMPLETED"
        ]);

        Game::create([
            "game_date" => new Carbon('2023-11-18'),
            "total_score" => 197,
            "user_id" => 1,
            "location_id" => 1,
            "ball_id" => 2,
            "status" => "COMPLETED"
        ]);

        Game::create([
            "game_date" => new Carbon('2023-12-18'),
            "total_score" => 127,
            "user_id" => 1,
            "location_id" => 3,
            "ball_id" => 2,
            "status" => "COMPLETED"
        ]);

        Game::create([
            "game_date" => new Carbon('2023-12-18'),
            "total_score" => 138,
            "user_id" => 1,
            "location_id" => 2,
            "ball_id" => 1,
            "status" => "COMPLETED"
        ]);

        Game::create([
            "game_date" => new Carbon('2023-11-18'),
            "total_score" => 153,
            "user_id" => 1,
            "location_id" => 2,
            "ball_id" => 1,
            "status" => "COMPLETED"
        ]);
    }
}
