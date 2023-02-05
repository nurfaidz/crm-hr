<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    use HasRoles;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(1);

        $worker = User::factory()->create();
        $worker->assignRole(2);

        $manager = User::factory()->create();
        $manager->assignRole(3);

        $humanResources = User::factory()->create();
        $humanResources->assignRole(4);

        $finance = User::factory()->create();
        $finance->assignRole(5);
    }
}
