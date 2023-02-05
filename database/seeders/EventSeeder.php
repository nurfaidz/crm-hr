<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
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
            DB::table('event')->insert([
                'start' => $faker->date,
                'nama_event' => $faker->name,
                'tempat_event' => $faker->citySuffix,
                'finish' => $faker->date,
            ]);
        }
    }
}
