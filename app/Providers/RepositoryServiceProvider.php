<?php

namespace App\Providers;

use App\Interfaces\ApprovalInterface;
use App\Interfaces\AttendanceInterface;
use App\Interfaces\ConfigInterface;
use App\Interfaces\EmployeeEducationInterface;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\LeaveApplicationInterface;
use App\Interfaces\LeavePeriodInterface;
use App\Interfaces\OvertimeInterface;
use App\Interfaces\WorkDaysInterface;
use App\Interfaces\WorkShiftsInterface;
use App\Interfaces\ManualAttendanceInterface;
use App\Interfaces\MedicalReimbursementInterface;
use App\Interfaces\NotificationsInterface;
use App\Interfaces\UpdateDataInterface;
use App\Interfaces\SortingInterface;
use App\Interfaces\SavingandLoanInterface;
use App\Repositories\SavingandLoanRepository;
use App\Repositories\SortingRepository;
use App\Repositories\ApprovalRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\ConfigRepository;
use App\Repositories\EmployeeEducationRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\LeaveApplicationRepository;
use App\Repositories\LeavePeriodRepository;
use App\Repositories\OvertimeRepository;
use App\Repositories\WorkDaysRepository;
use App\Repositories\WorkShiftsRepository;
use App\Repositories\ManualAttendanceRepository;
use App\Repositories\MedicalReimbursementRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\UpdateDataRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LeavePeriodInterface::class, LeavePeriodRepository::class);
        $this->app->bind(LeaveApplicationInterface::class, LeaveApplicationRepository::class);
        $this->app->bind(MedicalReimbursementInterface::class, MedicalReimbursementRepository::class);
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(WorkDaysInterface::class, WorkDaysRepository::class);
        $this->app->bind(WorkShiftsInterface::class, WorkShiftsRepository::class);
        $this->app->bind(ApprovalInterface::class, ApprovalRepository::class);
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeeEducationInterface::class, EmployeeEducationRepository::class);
        $this->app->bind(OvertimeInterface::class, OvertimeRepository::class);
        $this->app->bind(ConfigInterface::class, ConfigRepository::class);
        $this->app->bind(AttendanceInterface::class, AttendanceRepository::class);
        $this->app->bind(ManualAttendanceInterface::class, ManualAttendanceRepository::class);
        $this->app->bind(UpdateDataInterface::class, UpdateDataRepository::class);
        $this->app->bind(NotificationsInterface::class, NotificationsRepository::class);
        $this->app->bind(SortingInterface::class, SortingRepository::class);
        $this->app->bind(SavingandLoanInterface::class, SavingandLoanRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
