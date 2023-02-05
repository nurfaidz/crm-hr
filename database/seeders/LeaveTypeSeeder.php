<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveType::create([
            'leave_type_name' => 'Annual Leave',
            'num_of_day' => 12,
            'type' => 0
        ]);

        $leaveTypes = ['Big Leave', 'Maternity Leave', 'Sick Leave', 'Important Leave', 'Unpaid Leave'];
        $i = 1;

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create([
                'leave_type_name' => $leaveType,
                'type' => $i++
            ]);
        }
    }
}
