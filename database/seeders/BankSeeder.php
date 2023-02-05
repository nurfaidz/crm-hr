<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();
        DB::table('banks')->truncate();
        DB::table('banks')->insert(
            [
                [
                    'bank_name' => 'BCA',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Mandiri',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'BNI',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'BRI',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'CIMB Niaga',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Muamalat',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Jenius',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'BTN',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Permata',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Danamon',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Bukopin',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'BNI Syariah',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'BRI Syariah',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Syariah Mandiri',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'bank_name' => 'Bank Syariah Indonesia',
                    'created_at' => $time,
                    'updated_at' => $time
                ],
            ]

        );
    }
}
