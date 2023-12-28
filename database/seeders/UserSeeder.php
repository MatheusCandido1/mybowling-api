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
            "avatar" => 'https://github.com/matheuscandido1.png'
        ]);

        $profile = Profile::create([
            "first_access" => true,
            "user_id" => 1
        ]);

        $user->profile()->save($profile);

        $user2 = User::create([
            "name" => "Gabriel Santos",
            "email" => "gabriel@email.com",
            "password" => bcrypt("secret"),
            "avatar" => 'https://github.com/gabmaxs.png'
        ]);

        $profile2 = Profile::create([
            "first_access" => true,
            "user_id" => 1
        ]);

        $user2->profile()->save($profile2);
    }
}
