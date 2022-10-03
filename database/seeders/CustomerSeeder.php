<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'nama' => 'Umum',
            'telepon' => fake()->e164PhoneNumber(),
            'alamat' => fake()->address(),
        ]);
        Customer::factory(100)->create();
    }
}
