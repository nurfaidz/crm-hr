<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class LateMealTransSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('late_mealtrans')->insert(
            [
                [
                    'start_minutes' => 0,
                    'percentage' => 100
                ],
                [
                    'start_minutes' => 1,
                    'percentage' => 75
                ],
                [
                    'start_minutes' => 16,
                    'percentage' => 50
                ],
                [
                    'start_minutes' => 30,
                    'percentage' => 0
                ]
            ]
        );
    }
}
