<?php

namespace Database\Seeders;

use App\Models\WorkShift;
use Illuminate\Database\Seeder;

class WorkShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'company_id' => 1,
                'shift_name' => 'Shift Normal',
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'max_arrival' => '08:05:00'
            ],
            [
                'company_id' => 1,
                'shift_name' => 'Shift Khusus',
                'start_time' => '08:30:00',
                'end_time' => '15:30:00',
                'max_arrival' => '08:35:00'
            ]
        ];

        foreach ($datas as $data) {
            WorkShift::create([
                'company_id' => $data['company_id'],
                'shift_name' => $data['shift_name'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'max_arrival' => $data['max_arrival'],
            ]);
        }
    }
}
