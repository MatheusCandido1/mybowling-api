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
            "password" => bcrypt("secret"),
            "avatar" => 'avatars/FgsLS8RnmbEbUtoI3kWsHR7CsEJ9yeD8rfHNXDxM.jpg'
        ]);

        $profile = Profile::create([
            "first_access" => true
        ]);

        $user->profile()->save($profile);


        $user3 = User::create([
            "name" => "Test User",
            "email" => "test@email.com",
            "password" => bcrypt("secret"),
            "avatar" => ""
        ]);

        $profile3 = Profile::create([
            "first_access" => true
        ]);

        $user3->profile()->save($profile3);
    }
}
