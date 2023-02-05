<?php

namespace Database\Seeders;

use App\Models\Branches;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create('id_ID');
        // for ($i = 0; $i <= 10; $i++) {
        //     DB::table('branches')->insert([
        //         'branch_name' => $faker->company,
        //         'company_id'  => 1
        //     ]);
        // }

        $datas = [
            "Proxsis Solusi Humaka" => ['Proxsis Solusi Humaka Training (PSH Training)', 'Proxsis Solusi Humaka Consultation (PSH Consultation)', 'Proxsis Solusi Humaka Assessment (PSH Asesmen)', 'Proxsis Solusi Humaka Management Service (PSH Management Services)'],
            "Proxsis Global Solusi" => ['IT Consulting', 'Biztech Academy', 'IT Governance Indonesia Training (ITGID Training)', 'IT Global Solusi (ITGS)', 'Proxsis Global Solusi (PGS)', 'Proxsis Global Solusi Consulting (PGS Consulting)'],
            "Proxsis Strategi Bisnis" => ['Proxsis Bisnis Solusi Consulting (PBS Consulting)', 'Proxsis Bisnis Solusi Training (PBS Training)'],
            "Proxsis Manajemen Internasional" => ['Proxsis East Consulting (PEC)', 'Proxsis East Training (PET)', 'Proxsis East Consulting (PEC) & Proxsis East Training (PET)', 'Proxsis East Consulting (PEC), Proxsis East Training (PET), Indonesia East Training (IET), Synergy East Training (SET)', 'Proxsis East Consulting (PEC), Proxsis East Training (PET), Indonesia East Training (IET), Synergy East Training (SET), Synergy West Training (SWT)'], "Sinergi Solusi Indonesia" => ['Biztech', 'Synergy East Training (SET) & Indonesia East Training (IET)
            ', 'Synergy West Training (SWT)'],"Sinergi Solusi Pratama" => ['Indonesia Safety Center (ISC)', 'Indonesia Safety Center (ISC) & Synergy Solusi (SS)', 'Synergy Solusi (SS)', 'Synergi Solusi Global (SSG)'],
        ];

        foreach ($datas as $branch => $departments) {
            $branch = Branches::create([
                'branch_name' => $branch,
                'company_id' => 1,
            ]);

            foreach ($departments as  $department) {
                Department::create([
                    'code' => Str::random(5),
                    'department_name' => $department,
                    'department_branch_id' => $branch->branch_id,
                    'manager' => 3
                ]);
            }
        }
    }
}
