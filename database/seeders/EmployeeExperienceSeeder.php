<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmployeeExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_experiences')->insert([
            'employee_id' => "1",
            'corporate' => "PT Persada",
            'Position' => "Project Manager",
            'Years' => "2022",
            'description' => "Tes"
        ]);
    }
}
