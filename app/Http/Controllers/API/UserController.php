<?php

namespace App\Http\Controllers\API;

use App\Helpers\DateHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\LeaveApplication;
use App\Repositories\EmployeeRepository;
use App\Repositories\LeavePeriodRepository;
use App\Repositories\WorkDaysRepository;
use App\Repositories\WorkShiftsRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Get(
 *     path="/api/user",
 *     tags={"My Profile"},
 *     summary="Get all my informations",
 *     operationId="userProfile",
 *     security={{"bearerAuth":{}}}, 
 *      
 *     @OA\Response(
 *         response="200",
 *         description="Profile data fetched"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Something went wrong"
 *     )
 * )
 */
class UserController extends Controller
{
    private 
        $employeeRepository,
        $workDaysRepository,
        $workShiftsRepository,
        $leavePeriodRepository;

    public function __construct(
        EmployeeRepository $employeeRepository,
        WorkDaysRepository $workDaysRepository,
        WorkShiftsRepository $workShiftsRepository,
        LeavePeriodRepository $leavePeriodRepository
        )
    {
        $this->employeeRepository = $employeeRepository;
        $this->workDaysRepository = $workDaysRepository;
        $this->workShiftsRepository = $workShiftsRepository;
        $this->leavePeriodRepository = $leavePeriodRepository;
    }

    public function getProfile()
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames();
            $data = Role::where('name', $role[0])->get();
      
            $permissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', '=', $data[0]->id)
            ->join('permissions', 'role_has_permissions.permission_id', 'permissions.id')
            ->get('name');

            $list_permissions = [];

            foreach($permissions as $p){
                array_push($list_permissions, $p->name);
            }

            unset($permissions);

            $employee = $this->employeeRepository->getEmployeeNameById($user->id);
            $workshifts = $this->workDaysRepository->getWorkingDays($employee->branch_id, $employee->work_shift_id);
            $workdays = [];
            foreach($workshifts as $workshift){
                array_push($workdays, $workshift->day_name);
            }
            $minutesDiff = $this->workShiftsRepository->getWorkingHours($employee->work_shift_id);
            $leavePeriods = $this->leavePeriodRepository->getLeavePeriodOnThisYear();

            unset($user->roles);

            $employeeJoinDate = Employee::where('employee_id', '=', Auth::user()->employee->employee_id)->first();
            $joinDate = $employeeJoinDate['date_of_joining'];
            $employeeJoinDate = Carbon::parse($employeeJoinDate['date_of_joining']);
            $employeeJoinDuration = $employeeJoinDate->diffInYears(Carbon::now()->toDateString());
    
            $joinDate = explode('-', $joinDate);
            $joinDateYear = (int)$joinDate[0];
            $firstYearArrayBatch = [];
            $secondYearArrayBatch = [];
    
            for ($i = 0; $i < 10; $i++) {
                $joinDateYear += 6;
                array_push($firstYearArrayBatch, $joinDateYear);
            }
    
            foreach ($firstYearArrayBatch as $value) {
                $value += 1;
                array_push($secondYearArrayBatch, $value);
                $value += 1;
                array_push($secondYearArrayBatch, $value);
            }

            $thisYearBigLeave = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->where('leave_applications.leave_type_id', '=', 2)
            ->whereYear('application_date', date('Y'))->first();
        
            $bigLeaveStatus = false;
            if ($thisYearBigLeave) {
                $bigLeaveYear = new Carbon($thisYearBigLeave['application_date']);
                $bigLeaveYear = $bigLeaveYear->year;

                if (in_array(date('Y'), $secondYearArrayBatch) && in_array($bigLeaveYear, $secondYearArrayBatch)) {
                    $bigLeaveStatus = !$bigLeaveStatus;
                }
            }
            
            

            $response = [
                'user' => $user,
                'role' => $data[0],
                'employee' => $employee,
                'leavePeriods' => $leavePeriods,
                'workingHours' => DateHelpers::minutesToHours($minutesDiff),
                'workdays' => $workdays,
                'permissions' => $list_permissions,
                'isBigleave' => $bigLeaveStatus
            ];

            return ResponseFormatter::success($response, 'Get User');
        } 
        catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }
}
