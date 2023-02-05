<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeBalance;

class EmployeeBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeId = [1, 2, 3, 4, 5];

        foreach ($employeeId as $id) {
            EmployeeBalance::create([
                'employee_id' => $id,
                'total_balance' => 6000000,
            ]);
        }
    }
}
