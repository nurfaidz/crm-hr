<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\ManualAttendanceInterface;
use App\Helpers\ResponseFormatter;
use App\Models\Branch;
use App\Models\Employee;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Interfaces\EmployeeInterface;

class ManualAttendanceController extends Controller
{
    private $manualAttendanceInterface;
    private $employeeInterface;

    public function __construct(ManualAttendanceInterface $manualAttendanceInterface, EmployeeInterface $employeeInterface)
    {
        $this->manualAttendanceInterface = $manualAttendanceInterface;
        $this->employeeInterface = $employeeInterface;
    }

    public function getAllManualAttendanceAppr()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->join('department', 'employees.department_id', '=', 'department.department_id')->firstOrFail();
            $env = env('APP_URL');

            $manualAttendanceList = $this->manualAttendanceInterface->getAllManualAttendanceAppr($employee->branch_id, $employee->employee_id)
            ->where('department.code', '=', $employee->code)
            ->where('attendances.status', '=', 'apd')
            ->get([
                'attendances.id',
                'employees.first_name',
                'employees.last_name',
                'branches.branch_name',
                'attendances.date',
                'attendances.note',
                'attendances.image_manual_attendance as file_path',
                // 'attendances.status',
                'status_codes.short',
            ]);

            if (count($manualAttendanceList) <= 0) return ResponseFormatter::success([],'No data available at the moment', 204);

            foreach ($manualAttendanceList as $list) {

                $file_path = [];

                if ($list->file_path !== null) {
                    $exp = explode('/', $list->file_path);

                    $obj = (object)[];
                    $obj->link = $list->file_path;
                    $obj->filename = $exp[count($exp)-1];

                    array_push($file_path, $obj);
                }
                $list->file_path = $file_path;
            }

            return ResponseFormatter::success($manualAttendanceList, 'Get Manual Attendances Success');
        } 
        catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function getManualAttendanceId($Id)
    {
        try
        {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $manualAttendance = $this->manualAttendanceInterface->getManualAttendanceById($Id, $employee->employee_id);

            $manualAttendanceAppr = (object)[];

            if ($manualAttendance === null) {
                $response = [
                    'check_in' => 0,
                    'check_out' => 0,
                    'status' => null,
                    'notes' => null,
                    'date' => 0
                ];

                return ResponseFormatter::error($response, 'No data available at the moment', 204);
            }
            
            if(!is_null($manualAttendance->status_by)){
                $manualAttendanceAppr = $this->employeeInterface->getEmployeeById($manualAttendance->status_by)
                ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                ->first([
                    'employees.user_id',
                    'employees.employee_id',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.image',
                    'job_positions.job_position',
                ]);

                $manualAttendanceAppr->image = $this->employeeInterface->getEmployeePicture($manualAttendanceAppr->user_id);
            }
            
            if ($manualAttendance->image_manual_attendance !== null) {
                $file_path = [];

                if ($manualAttendance->image_manual_attendance !== null) {
                    $exp = explode('/', $manualAttendance->image_manual_attendance);

                    $obj = (object)[];
                    $obj->link = $manualAttendance->image_manual_attendance;
                    $obj->filename = $exp[count($exp) - 1];

                    array_push($file_path, $obj);
                }
                $manualAttendance->image_manual_attendance = $file_path;
            }

            return ResponseFormatter::success([
                'requester' => [
                    'user_id' => $manualAttendance->user_id,
                    'employee_id' => $manualAttendance->employee_id,
                    'first_name' => $manualAttendance->first_name,
                    'last_name' => $manualAttendance->last_name,
                    'image' => $manualAttendance->image,
                    'department' => $manualAttendance->department_name,
                    'job_position' => $manualAttendance->job_position
                ],
                'attendance' => [
                    'date' => $manualAttendance->date,
                    'check_in' => $manualAttendance->check_in,
                    'check_out' => $manualAttendance->check_out,
                    'status' => $manualAttendance->short,
                    'note' => $manualAttendance->note,
                    'location' => $manualAttendance->location,
                    'image' => $manualAttendance->image_manual_attendance
                ],
                'approver' => $manualAttendanceAppr
            ], 'Get Manual Attendance Detail Success');

        }catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage(),
                'line' => $error->getLine()
            ], 'Something went wrong', 500);
        }
    }

}
