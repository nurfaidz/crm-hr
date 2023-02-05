<?php

namespace Database\Seeders;

use App\Models\SalaryType;
use Illuminate\Database\Seeder;

class SalaryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Monthly', 'Annualy', 'Hourly', 'Daily', 'Weekly'];

        foreach ($types as $type) {
            SalaryType::create([
                'salary_type' => $type
            ]);
        }
    }
}
