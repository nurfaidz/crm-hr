<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'company_name' => 'PT Proxsis Solusi Bisnis',
            'company_address' => 'Jl. Kuningan Mulia Kav. 9, Kawasan Bisnis Epicentrum, Jakarta Selatan - Indonesia 12980',
            'company_phone' => '(021) 837 086 79',
            'company_email' => 'cs@proxsisgroup.com',
            'company_website' => 'https://proxsisgroup.com/consulting/'
        ]);
    }
}
