<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $demoUser = User::create([
            'name' => $faker->firstName,
            'email' => 'superadmin@app.com',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
        ]);

        $demoUser->assignRole('superadmin');

        $demoUser = User::create([
            'name' => $faker->firstName,
            'email' => 'kasir@app.com',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
        ]);

        $demoUser->assignRole('kasir');
    }
}
