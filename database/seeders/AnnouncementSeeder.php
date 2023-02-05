<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i <= 9; $i++) {
            $announcement = Announcement::create([
                'user_id' => 1,
                'announcement_title' => $faker->sentence,
                'announcement_content' => $faker->paragraph(20),
                'announcement_status' => 'Unpublished',
            ]);
            File::copy(public_path('examples/file-example.pdf'), public_path('examples/file-example'.$announcement->announcement_id.'.pdf'));
            $file = public_path('examples/file-example'.$announcement->announcement_id.'.pdf');
            File::copy(public_path('examples/image-example.jpg'), public_path('examples/image-example'.$announcement->announcement_id.'.jpg'));
            $image = public_path('examples/image-example'.$announcement->announcement_id.'.jpg');
    
            $announcement->addMedia($file)->toMediaCollection('announcement_attachment');
            $announcement->addMedia($image)->toMediaCollection('announcement_image');
        }
    }
}
