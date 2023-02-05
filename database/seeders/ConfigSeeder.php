<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            'key' => 'overtimeWeekQouta',
            'value' => 18*60,
            'group' => 'overtime',
            'description' => 'total minutes quota per weeks'
        ]);

        Config::create([
            'key' => 'overtimeTodayMinimal',
            'value' => 1*60,
            'group' => 'overtime',
            'description' => 'minimal minutes quota per day'
        ]);


        Config::create([
            'key' => 'overtimeTodayMaximal',
            'value' => 4*60,
            'group' => 'overtime',
            'description' => 'maximal minutes quota per day'
        ]);
    }
}
