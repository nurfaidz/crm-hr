<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('approval')->insert([
                'tanggal_approval' => "2022-03-10",
                'user_id' => "Rizki",
                'keterangan' => "cuti",
                'status' => "pending"
            ]);
        
    }
}
