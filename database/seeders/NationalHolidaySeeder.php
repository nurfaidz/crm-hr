<?php

namespace Database\Seeders;

use App\Models\NationalHoliday;
use Illuminate\Database\Seeder;

class NationalHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDates = [
            '2022-01-01', '2022-02-01', '2022-02-28', '2022-03-03', '2022-04-15', '2022-04-29', '2022-05-01', '2022-05-02',
            '2022-05-16', '2022-05-26', '2022-06-01', '2022-07-09', '2022-07-30', '2022-08-17', '2022-10-08', '2022-12-25'
        ];
        $endDates = [
            '2022-01-01', '2022-02-01', '2022-02-28', '2022-03-03', '2022-04-15', '2022-04-29', '2022-05-01', '2022-05-03',
            '2022-05-16', '2022-05-26', '2022-06-01', '2022-07-09', '2022-07-30', '2022-08-17', '2022-10-08', '2022-12-25'
        ];
        for ($i = 0; $i < 8; $i++) {
            NationalHoliday::create([
                'holiday_id' => $i + 1,
                'start_date' => $startDates[$i],
                'end_date' => $endDates[$i]
            ]);
        }

        // NationalHoliday::create([
        //     'holiday_id' => 6,
        //     'start_date' => '2022-05-04',
        //     'end_date' => '2022-05-06'
        // ]);

        for ($i = 8; $i < 16; $i++) {
            NationalHoliday::create([
                'holiday_id' => $i + 1,
                'start_date' => $startDates[$i],
                'end_date' => $endDates[$i]
            ]);
        }
    }
}
