<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            DashboardBuilderSeeder::class,
            LateMealTransSeeder::class,
            BranchesSeeder::class,
            DepartmentSeeder::class,
            AnnouncementSeeder::class,
            BankSeeder::class,
            LeaveTypeSeeder::class,
            LeavePeriodSeeder::class,
            StatusCodeSeeder::class,
            ReligionSeeder::class,
            MaritalStatusSeeder::class,
            EmploymentStatusSeeder::class,
            SalaryTypeSeeder::class,
            DaysSeeder::class,
            WorkShiftSeeder::class,
            WorkDaysSeeder::class,
            DayOfWeekSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            OvertimeSeeder::class,
            AttendanceSeeder::class,
            JobClassSeeder::class,
            JobPositionSeeder::class,
            CompanySeeder::class,
            ManageHolidaySeeder::class,
            NationalHolidaySeeder::class,
            OptionLeaveSeeder::class,
            ConfigSeeder::class,
            EmployeeBalanceSeeder::class,
        ]);
    }
}
