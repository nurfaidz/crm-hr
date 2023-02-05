<?php

namespace Database\Seeders;

use App\Models\Days;
use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas =
            [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
            ];

        foreach ($datas as $data) {
            Days::create([
                'day_name' => $data,
            ]);
        }
    }
}
