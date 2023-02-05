<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeavePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();
        DB::table('leave_periods')->insert([
            'from_date' => '2022-01-01',
            'to_date' => '2022-12-31',
            'limit' => '12',
            "year" => "same",
            'expired_date' => '2023-06-30',
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }
}
