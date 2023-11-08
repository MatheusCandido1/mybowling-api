<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frame;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Game 1
        Frame::create([
            "game_id" => 1,
            "frame_number" => 1,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 30,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 2,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 50,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 3,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 69,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 4,
            "first_shot" => 0,
            "second_shot" => 9,
            "points" => 9,
            "score" => 78,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 5,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 108,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 6,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 138,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 7,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 167,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 8,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 186,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 9,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 195,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 1,
            "frame_number" => 10,
            "first_shot" => 10,
            "second_shot" => 10,
            "third_shot" => 10,
            "points" => 30,
            "score" => 225,
            "status" => "completed"
        ]);

        // Game 2
        Frame::create([
            "game_id" => 2,
            "frame_number" => 1,
            "first_shot" => 9,
            "second_shot" => 1,
            "points" => 30,
            "score" => 20,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 2,
            "frame_number" => 2,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 40,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 2,
            "frame_number" => 3,
            "first_shot" => 9,
            "second_shot" => 1,
            "points" => 10,
            "score" => 60,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 2,
            "frame_number" => 4,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 77,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 2,
            "frame_number" => 5,
            "first_shot" => 7,
            "second_shot" => 0,
            "is_split" => 1,
            "split" => '6-7-10',
            "points" => 7,
            "score" => 84,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 2,
            "frame_number" => 6,
            "first_shot" => 6,
            "second_shot" => 2,
            "is_split" => 1,
            "split" => '4-6-7-10',
            "points" => 8,
            "score" => 92,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 2,
            "frame_number" => 7,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 101,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 2,
            "frame_number" => 8,
            "first_shot" => 8,
            "second_shot" => 2,
            "points" => 10,
            "score" => 111,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 2,
            "frame_number" => 9,
            "first_shot" => 0,
            "second_shot" => 9,
            "points" => 9,
            "score" => 120,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 2,
            "frame_number" => 10,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 129,
            "status" => "completed"
        ]);

        // Game 3
        Frame::create([
            "game_id" => 3,
            "frame_number" => 1,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 30,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 2,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 55,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 3,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 75,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 4,
            "first_shot" => 5,
            "second_shot" => 5,
            "points" => 10,
            "score" => 94,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 5,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 103,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 6,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 132,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 7,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 151,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 8,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 160,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 3,
            "frame_number" => 9,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 180,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 3,
            "frame_number" => 10,
            "first_shot" => 9,
            "second_shot" => 1,
            "third_shot" => 7,
            "points" => 17,
            "score" => 197,
            "status" => "completed"
        ]);

        // Game 4
        Frame::create([
            "game_id" => 4,
            "frame_number" => 1,
            "first_shot" => 7,
            "second_shot" => 0,
            "points" => 7,
            "score" => 7,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 4,
            "frame_number" => 2,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 26,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 4,
            "frame_number" => 3,
            "first_shot" => 1,
            "second_shot" => 8,
            "points" => 7,
            "score" => 35,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 4,
            "frame_number" => 4,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 44,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 4,
            "frame_number" => 5,
            "first_shot" => 7,
            "second_shot" => 3,
            "points" => 10,
            "score" => 64,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 4,
            "frame_number" => 6,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 82,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 4,
            "frame_number" => 7,
            "first_shot" => 0,
            "second_shot" => 8,
            "points" => 8,
            "score" => 90,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 4,
            "frame_number" => 8,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 99,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 4,
            "frame_number" => 9,
            "first_shot" => 8,
            "second_shot" => 2,
            "points" => 10,
            "score" => 118,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 4,
            "frame_number" => 10,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 127,
            "status" => "completed"
        ]);

        // Game 5
        Frame::create([
            "game_id" => 5,
            "frame_number" => 1,
            "first_shot" => 8,
            "second_shot" => 0,
            "points" => 0,
            "score" => 8,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 2,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 28,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 3,
            "first_shot" => 9,
            "second_shot" => 1,
            "points" => 10,
            "score" => 44,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 4,
            "first_shot" => 6,
            "second_shot" => 2,
            "is_split" => 1,
            "split" => '3-6-7-10',
            "points" => 8,
            "score" => 52,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 5,
            "first_shot" => 7,
            "second_shot" => 1,
            "points" => 8,
            "score" => 60,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 6,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 77,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 7,
            "first_shot" => 6,
            "second_shot" => 1,
            "points" => 7,
            "score" => 84,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 8,
            "first_shot" => 8,
            "second_shot" => 2,
            "is_split" => 1,
            "split" => '7-9',
            "points" => 10,
            "score" => 101,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 9,
            "first_shot" => 7,
            "second_shot" => 3,
            "points" => 10,
            "score" => 118,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 5,
            "frame_number" => 10,
            "first_shot" => 7,
            "second_shot" => 3,
            "third_shot" => 10,
            "points" => 20,
            "score" => 138,
            "status" => "completed"
        ]);

        // Game 6

        Frame::create([
            "game_id" => 6,
            "frame_number" => 1,
            "first_shot" => 9,
            "second_shot" => 0,
            "points" => 9,
            "score" => 9,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 2,
            "first_shot" => 8,
            "second_shot" => 2,
            "points" => 10,
            "score" => 28,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 3,
            "first_shot" => 9,
            "second_shot" => 1,
            "points" => 10,
            "score" => 46,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 4,
            "first_shot" => 8,
            "second_shot" => 2,
            "points" => 10,
            "score" => 66,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 5,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 93,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 6,
            "first_shot" => 10,
            "second_shot" => 0,
            "points" => 10,
            "score" => 110,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 6,
            "frame_number" => 7,
            "first_shot" => 7,
            "second_shot" => 0,
            "is_split" => 1,
            "split" => '6-7-10',
            "points" => 7,
            "score" => 117,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 6,
            "frame_number" => 8,
            "first_shot" => 0,
            "second_shot" => 9,
            "points" => 9,
            "score" => 126,
            "status" => "completed"
        ]);
        Frame::create([
            "game_id" => 6,
            "frame_number" => 9,
            "first_shot" => 8,
            "second_shot" => 2,
            "points" => 10,
            "score" => 144,
            "status" => "completed"
        ]);

        Frame::create([
            "game_id" => 6,
            "frame_number" => 10,
            "first_shot" => 8,
            "second_shot" => 1,
            "is_split" => 1,
            "split" => '7-9',
            "points" => 9,
            "score" => 153,
            "status" => "completed"
        ]);

    }
}
