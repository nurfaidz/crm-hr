<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class EmployeeSkill extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $i = 1;
        DB::table('employe_skills')->insert([
            'employee_id' => "1",
            'issued_by' => "Project Manager",
            'issued_date' => $faker->date,
            'exp_date' => $faker->date,
            'tags' => "Tes",
            'credentials' => "tes"
        ]); 
    }
}
