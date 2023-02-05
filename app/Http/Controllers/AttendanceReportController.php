<?php

namespace App\Http\Controllers;
// require 'vendor/autoload.php';
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branches;
use App\Models\Employee;
use App\Models\Attendance;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceReportController extends Controller
{

    public function attendanceSummary(Request $request)
    {
        // $department = Department::where('department_id', $request->department)
        // ->where('department_branch_id', $request->branch)
        // ->first();
        
        // $employees = Employee::where('employee_department_id', $request->department)
        // ->first()
        // ->getOriginal();
        // // dd($employees);

        // dd($attendances);
        $departments = Department::all();
        $branches = Branches::all();
        $attendances = Attendance::with('user');

        if(request('department') || request('branch')){
            $department = Department::where('department_branch_id', $request->branch)->get();
            // dd($department);
            if($department !== null){
                $department = Department::where('department_branch_id', $request->branch)
                                        ->where('department_id', $request->department)->first();
                if($department !== null){
                    $employees = Employee::where('employee_department_id', $department->department_id)->first();
                    if($employees !== null){
                        $employees = Employee::where('employee_department_id', $department->department_id)->get();
                        $attendances = Attendance::join('employees', 'employees.user_id', '=', 'attendances.user_id')
                                        ->where('employee_department_id', $request->department);
                        $month = date("m",strtotime(request('date')));
                        $year = date("Y",strtotime(request('date')));
                        $attendances = $attendances->whereRaw('MONTH(check_in) = '.$month);
                        $attendances = $attendances->whereRaw('YEAR(check_in) = '.$year);
                        return view('attendances.attendanceSummaryReport.index', [
                            'branches' => $branches,
                            'br' => $request->branch,
                            'departments' => $departments,
                            'de' => $request->department,
                            'attendances' => $attendances->get(),
                            'month' => request('date'),
                        ]);
                    }else{
                        return view('attendances.attendanceSummaryReport.index', [
                            'branches' => $branches,
                            'br' => $request->branch,
                            'departments' => $departments,
                            'de' => $request->department,
                            'attendances' => $attendances->where('id', 0)->get(),
                            'month' => request('date'),
                        ]);
                    }
                }else{
                    return view('attendances.attendanceSummaryReport.index', [
                        'branches' => $branches,
                        'br' => $request->branch,
                        'departments' => $departments,
                        'de' => $request->department,
                        'attendances' => $attendances->where('id', 0)->get(),
                        'month' => request('date'),
                    ]);
                }
            }
        }

        return view('attendances.attendanceSummaryReport.index', [
            'branches' => $branches,
            'br' => null,
            'departments' => $departments,
            'de' => null,
            'attendances' => $attendances->get(),
            'month' => null,
        ]);
    }

    public function attendanceSummaryReport(Request $request)
    {
        $branch_list = $this->commonRepository->branchList();
        if ($request->month) {
            $month = $request->month;
        } else {
            $month = date("Y-m");
        }

        $cutoff     = CutOffPeriod::where('month', $month)->first();

        if ($cutoff) {
            $start  = $cutoff->start;
            $end    = $cutoff->end;
        } else {
            $start  = $month . '-01';
            $end    = date("Y-m-t", strtotime($start));
        }

        $monthAndYear   = explode('-', $month);
        $month_data     = $monthAndYear[1];
        $dateObj        = DateTime::createFromFormat('!m', $month_data);
        $monthName      = $dateObj->format('F');
        $startName      = DateTime::createFromFormat('Y-m-d', $start)->format('d F');
        $endName        = DateTime::createFromFormat('Y-m-d', $end)->format('d F');


        //        $monthToDate   = findMonthToAllDate($month);
        $monthToDate   = rangeDateToAllDate($start, $end);
        $leaveType     = LeaveType::get();

        $branch_id = 1;
        if (!empty($request->branch_id)) $branch_id = $request->branch_id;
        $result        = $this->attendanceRepository->findAttendanceSummaryReport($month, $branch_id);

        return view(
            'admin.attendance.report.summaryReport',
            [
                'results'       => $result,
                'monthToDate'   => $monthToDate,
                'month'         => $month,
                'leaveTypes'    => $leaveType,
                'monthName'     => $monthName,
                'startName'     => $startName,
                'endName'       => $endName,
                'branch_list'   => $branch_list,
                'branch_id'     =>$branch_id
            ]
        );
    }


    public function downloadAttendanceSummaryReport($date, $department_id, $branch_id)
    {
        // $monthToDate   = findMonthToAllDate($month);
        // $leaveType     = LeaveType::get();
        // $result        = $this->attendanceRepository->findAttendanceSummaryReport($month, $branch_id);
        
        // $monthAndYear   = explode('-', $month);
        // $month_data     = $monthAndYear[1];
        // $dateObj        = DateTime::createFromFormat('!m', $month_data);
        // $monthName      = $dateObj->format('F');

        // $data = [
        //     'results'   => $result,
        //     'month'     => $month,
        //     'printHead' => $printHead,
        //     'monthToDate' => $monthToDate,
        //     'leaveTypes' => $leaveType,
        //     'monthName' => $monthName,
        // ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $departments = Department::all();
        $branches = Branches::all();
        $attendances = Attendance::all();

        $attendances->where('user_id',$department_id);
        $attendances->where('user_id', $branch_id);
        $attendances->where('check_in', $date);
        $i = 1;
        foreach($attendances as $attendance){
            $sheet->setCellValue('A'.$i, $date)
                ->setCellValue('B'.$i, $attendance->user->name)
                ->setCellValue('C'.$i, $attendance->check_in)
                ->setCellValue('D'.$i, $attendance->check_out)
                ->setCellValue('E'.$i, $attendance->work_time)
                ->setCellValue('F'.$i, '80:00')
                ->setCellValue('G'.$i, $attendance->late_time)
                ->setCellValue('H'.$i, 0)
                ->setCellValue('I'.$i, 'Hadir');
        }
    
            $writer = new Xlsx($spreadsheet);
    
            // $pdf = PDF::loadView('admin.attendance.report.pdf.attendanceSummaryReportPdf', $data);
            // $pdf->setPaper('A4', 'landscape');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode('hello.xlsx').'"');
            $writer->save('php://output');
        
            // return response()->json([
            //     "error" => true,
            //     "message" => "Something Went Wrong"
            // ]);
    }
}
