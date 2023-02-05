<?php

namespace App\Console;

use App\Commands\Console\OvertimePendingCommands;
use App\Console\Commands\OvertimePendingCron;
use App\Models\LeaveApplication;
use App\Models\Attendance;
use Carbon\Carbon;
// use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        OvertimePendingCron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:overtimepending')->cron('* 6 * * *');

        $schedule->command('auth:clear-resets')->hourly();
        
        $schedule->call(function () {
            $leaveApplication = LeaveApplication::all();
            // $datetime = new DateTime();
            foreach ($leaveApplication as $leave_Application) {
                if ($leave_Application->status !== 'cls') {
                    LeaveApplication::where('leave_application_id', $leave_Application->leave_application_id)
                        ->update([
                            'status'     => 'cls',
                            'close_date' => date('Y-m-d')
                        ]);
                }
            }
        })->monthlyOn(20, '00:00');

        $schedule->call(function () {
            $date = Carbon::now();
            $checks = Attendance::leftJoin('employees', 'attendances.employee_id', '=', 'employees.employee_id')
                    ->leftJoin('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
                    ->where('work_shifts.end_time', '<', $date)
                    ->whereNull('attendances.check_out')
                    ->get();
            foreach ($checks as $check) {
                    $timeDiff = Carbon::now()->diff($check->check_in);
                    $workingHour = $timeDiff->format('%h:%i:%s');
                    Attendance::where('id', $check->id)
                        ->update([
                            'check_out'     => $date,
                            'working_hour'  => $workingHour,
                            'note_check_out'=> 'This was checkout by system automatically'
                        ]);
                }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
