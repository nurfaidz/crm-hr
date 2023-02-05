<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DashboardBuilder;
use App\Models\UserHasDashboard;

class DashboardBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $widgets = [
            [
                'name' => 'Attendance'
            ],
            [
                'name' => 'Announcement'
            ],
            [
                'name' => 'Attendance History'
            ],
            [
                'name' => 'Presence'
            ],
            [
                'name' => 'Calendar & Events'
            ],
            [
                'name' => 'Approval'
            ],
            [
                'name' => 'Task'
            ],
            [
                'name' => 'Quick Links'
            ],
            [
                'name' => 'Teammates'
            ],
        ];

        foreach ($widgets as $widget) {
            DashboardBuilder::create($widget);
        }

        $userWidgets = [
            [
                'user_id' => 1,
                'dashboard_id' => 1,
                'sequence' => 1
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 2,
                'sequence' => 2
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 3,
                'sequence' => 3
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 4,
                'sequence' => 4
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 5,
                'sequence' => 5
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 6,
                'sequence' => 6
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 7,
                'sequence' => 7
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 8,
                'sequence' => 8
            ],
            [
                'user_id' => 1,
                'dashboard_id' => 9,
                'sequence' => 9
            ],
        ];

        foreach ($userWidgets as $userWidget) {
            UserHasDashboard::create($userWidget);
        }
    }
}
