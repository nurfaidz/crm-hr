<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $religions = ['Islam', 'Protestantism', 'Catholicism', 'Hinduism', 'Buddhism', 'Confucianism'];

        foreach ($religions as $religion) {
            Religion::create([
                'religion' => $religion
            ]);
        }
    }
}
