<?php

namespace Database\Seeders;

use App\Models\JobClass;
use Illuminate\Database\Seeder;

class JobClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = ['Accounting', 'Consultant', 'Finance', 'Marketing', 'Technical Support'];

        foreach ($classes as $class) {
            JobClass::create([
                'job_class' => $class,
                'role_id' => 1,
            ]);
        }
    }
}
