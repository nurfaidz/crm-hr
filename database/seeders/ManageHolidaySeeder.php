<?php

namespace Database\Seeders;

use App\Models\ManageHoliday;
use Illuminate\Database\Seeder;

class ManageHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidays = ['Hari Tahun Baru', 'Tahun Baru Imlek', 'Isra Mikraj Nabi Muhammad', 'Hari Suci Nyepi', 'Wafat Isa Almasih',
            'Cuti Bersama Idul Fitri', 'Hari Buruh Internasional', 'Hari Idul Fitri', 'Hari Raya Waisak', 'Kenaikan Isa Al Masih',
            'Hari Lahir Pancasila', 'Idul Adha', 'Tahun Baru Hijriah', 'Hari Proklamasi Kemerdekaan R.I.', 'Maulid Nabi Muhammad',
            'Hari Raya Natal'];
        
        foreach ($holidays as $holiday) {
            ManageHoliday::create([
                'holiday_occasion' => $holiday,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam efficitur convallis est et fringilla. Ut at arcu imperdiet, consectetur sapien sit amet, accumsan lorem. Curabitur molestie mattis nisi. Nullam non felis ex. Duis lectus sapien, aliquam eu augue ut, luctus malesuada lorem. Fusce malesuada nunc erat, at convallis neque dictum nec. Suspendisse dictum enim sed arcu placerat iaculis.',
                'status' => 1
            ]);
        }
    }
}
