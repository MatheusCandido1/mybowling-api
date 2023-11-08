<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            "name" => "South Point Bowling Center",
        ]);

        Location::create([
            "name" => "Sunset Station Bowling Center",
        ]);

        Location::create([
            "name" => "Orleans Bowling Center",
        ]);

        Location::create([
            "name" => "Gold Coast Bowling Center",
        ]);
    }
}
