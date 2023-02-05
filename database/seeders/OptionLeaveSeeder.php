<?php

namespace Database\Seeders;

use App\Models\OptionLeave;
use Illuminate\Database\Seeder;

class OptionLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $optionLeaves = ['Getting Married', 'Child\'s Wedding', 'Child\'s Circumcision', 'Child\'s Baptism', 'Death of a Family Member', 'Wife Gives Birth'];

        foreach ($optionLeaves as $optionLeave) {
            OptionLeave::create([
                'option_leave' => $optionLeave
            ]);
        }
    }
}
