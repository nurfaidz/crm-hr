<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmployeeEmergencyContact extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_emergency_contacts')->insert([
            'employee_id' => "1",
            'name' => "PT Persada",
            'connection' => "Mother",
            'contact' => "0888777666",
        ]);
    }
}
