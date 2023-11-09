<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            "name" => "Matheus Carvalho",
            "email" => "matheus@email.com",
            "password" => bcrypt("secret")
        ]);

        $profile = Profile::create([
            "first_access" => true,
            "user_id" => 1
        ]);

        $user->profile()->save($profile);
    }
}
