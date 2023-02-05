<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Branches;
use App\Models\Employee;
use App\Models\WorkShift;
use App\Models\Department;
use App\Interfaces\SortingInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Interfaces\EmployeeInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FindEmployeeController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     private EmployeeInterface $employeeInterface;
     private SortingInterface $sortingInterface;

     public function __construct(
        EmployeeInterface $employeeInterface,
        SortingInterface $sortingInterface
     )
     {
         $this->employeeInterface = $employeeInterface;
         $this->sortingInterface = $sortingInterface;
     }

     public function index()
     {
         return view('find-employee.index');
     }

     public function findWoker()
     {
        $date = Carbon::now();
        $today = Carbon::today();
        $employee = $this->sortingInterface->getEmployeeWorker()
            ->whereDate('date', $today)
            ->where('check_in', '<=', $date)
            ->where('check_out', '>=', $date)
            ->where('short', 'Check In')
            ->get();
        // ->get([
        //     'employees.employee_id',
        //     'employees.first_name',
        //     'employees.last_name',
        //     'branches.branch_name',
        //     'department.department_name',
        //     'job_positions.job_position',
        //     'job_classes.job_class',
        // ]);
        // dd($date,$employee);

        return datatables()->of($employee)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
            })
            ->make(true);

     }

     public function workerNotPresent()
     {
        $date = Carbon::now();
        $today = Carbon::today();
        $employee = $this->sortingInterface->getEmployeeWorker()
            ->whereIn('short', ['Day Off', 'Absence', 'Permission', 'Sick'])
            ->whereDate('date', $today)
            ->get();

        return datatables()->of($employee)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
            })
            ->make(true);
     }
     
}
