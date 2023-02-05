<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = ['Accounting Staff', 'IT Consultant', 'Financial Planner', 'Digital Marketing Leader', 'Technical Solution Specialist'];
        $faker = Faker::create('id_ID');
        $i = 1;

        foreach ($positions as $position) {
            JobPosition::create([
                'job_position' => $position,
                'department_id' => $faker->numberBetween(1, 14),
                'job_class_id' => $i++
            ]);
        }
    }
}
