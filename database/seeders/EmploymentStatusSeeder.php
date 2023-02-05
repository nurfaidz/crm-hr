<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Permanent', 'Internship', 'Contract', 'Probation'];

        foreach ($statuses as $status) {
            EmploymentStatus::create([
                'employment_status' => $status
            ]);
        }
    }
}
