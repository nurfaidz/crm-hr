<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class EmployeeEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            DB::table('employee_educations')->insert([
                'employee_id' => "1",
                'institution' => "Lawson Andy",
                'degree' => "Bachelor (S1)",
                'major' => "Civil Engineer",
                'entry_level' => "2016",
                'graduation_year' => "2019",
                'GPA' => "3.87"
            ]);
        }
    }

