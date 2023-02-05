<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Workdays;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class WorkDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $datas = [
            [
                'branch_id' => 1,
                'work_shift_id' => 1,
            ],
            [
                'branch_id' => 1,
                'work_shift_id' => 2,
            ],
            [
                'branch_id' => 2,
                'work_shift_id' => 1,
            ],
            [
                'branch_id' => 2,
                'work_shift_id' => 2,
            ],
        ];

        foreach ($datas as $data) {
            for($i = 2; $i <= 6; $i++){
                Workdays::create([
                    'branch_id' => $data['branch_id'],
                    'work_shift_id' => $data['work_shift_id'],
                    'days_id' => $i,
                    'published_at' => Carbon::now()
                ]);
            }
        }

    }
}
