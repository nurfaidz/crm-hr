<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create('id_ID');
        // $departmentName = [
        //     'Komisaris',
        //     'Direktur',
        //     'Sekretariat',
        //     'Akunting',
        //     'Keagenan',
        //     'Keuangan',
        //     'Logistik Kapal',
        //     'Pemasaran dan Operasi',
        //     'SMR-SPI',
        //     'Kepala Cabang Surabaya',
        //     'Kepala Sekretariat',
        //     'Operasional',
        //     'Accounting',
        //     'Kepala Cabang',
        // ];

        // foreach ($departmentName as $department_name) {
        //     Department::create([
        //         'code'                  => Str::random(5),
        //         'department_name'       => $department_name,
        //         'department_branch_id'  => $faker->randomElement([1, 2]),
        //         'manager'               => 3
        //     ]);
        // }
    }
}
