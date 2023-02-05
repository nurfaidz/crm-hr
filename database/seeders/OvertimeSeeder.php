<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 5; $i++){
            DB::table('overtimes')->insert([
                'overtime_id' => $i,
                'employee_id' => 4,
                'work_shift_name' => 'Shift Khusus',
                'work_shift_start' => '08:00:00',
                'work_shift_end' => '17:00:00',
                'date' => Carbon::now()->addDays($i),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'notes' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book',
                'created_at' => Carbon::now(),
                // 'update_time' => Carbon::now(),
                // 'update_by' => 1,
            ]);
        }
    }
}
