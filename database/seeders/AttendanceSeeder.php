<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $dateTime = new Carbon(date('Y-m-d') . ' 08:00:00');
        $date = new Carbon(date('Y-m-d'));
        
        for ($i = 0; $i < 10; $i++) {
            $dateTime->subDay();
            Attendance::create([
                'employee_id'       => $faker->numberBetween(1, 4),
                'overtime_id'       => null,
                'date'              => $date->subDay(),
                'check_in'          => $dateTime->copy(),
                'check_out'         => $dateTime->copy()->addHours(8),
                'late_duration'     => 30,
                'overtime_duration' => 120,
                'working_hour'      => '08:00:00',
                'note_check_in'     => $faker->sentence,
                'note_check_out'    => $faker->sentence,
                'status'            => 'acw',
                'status_by'         => 1
            ]);
        }
    }
}
