<?php

namespace Database\Seeders;

use App\Models\MaritalStatus;
use Illuminate\Database\Seeder;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Single', 'Married', 'Widowed', 'Divorced', 'Separated'];

        foreach ($statuses as $status) {
            MaritalStatus::create([
                'marital_status' => $status
            ]);
        }
    }
}
