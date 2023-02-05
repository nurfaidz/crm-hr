<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($x = 1; $x <= 10; $x++){
            DB::table('customer')->insert([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'nohp' => $faker->PhoneNumber,
                'email' => $faker->freeEmail,
                'facebook' => $faker->freeEmail,
                'instagram' => $faker->userName,
                'whatsapp' => $faker->phoneNumber,
                'company' => $faker->company,
            ]);
        }
    }
}
