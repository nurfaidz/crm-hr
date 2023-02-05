<?php

namespace App\Http\Controllers;

use App\Interfaces\EmployeeInterface;
use App\Interfaces\LeaveApplicationInterface;
use App\Interfaces\ManualAttendanceInterface;
use App\Interfaces\OvertimeInterface;
use App\Models\DashboardBuilder;
use App\Models\ModelHasRoles;
use App\Models\Role;
use App\Models\Announcement;
use App\Models\UserHasDashboard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\Approval;
use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\Overtime;
use App\Models\Workdays;
use App\Repositories\AnnouncementsRepository;
use App\Repositories\EmployeeRepository;

class HomeController extends Controller
{
    private $announcementRepository, $employeeRepository;
    private $overtimeInterface, $manualAttendanceInterface, $leaveApplicationInterface;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        EmployeeRepository $employeeRepository, 
        AnnouncementsRepository $announcementRepository, 
        OvertimeInterface $overtimeInterface, 
        ManualAttendanceInterface $manualAttendanceInterface,
        LeaveApplicationInterface $leaveApplicationInterface
    )
    {
        $this->middleware('auth');
        $this->announcementRepository = $announcementRepository;
        $this->employeeRepository = $employeeRepository;
        $this->overtimeInterface = $overtimeInterface;
        $this->manualAttendanceInterface = $manualAttendanceInterface;
        $this->leaveApplicationInterface = $leaveApplicationInterface;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasPermissionTo('dashboard.superadmin')) {
            $employeeName = $user->employee->first_name . ' ' . $user->employee->last_name;
            return view('dashboard.superadmin', compact('employeeName'));
        } else {
            $employeeName = $user->employee->first_name . ' ' . $user->employee->last_name;
            $employee = $this->employeeRepository->getEmployee(Auth::user()->id)->join('department', 'employees.department_id', '=', 'department.department_id')->firstOrFail();
            $approval = Approval::with('user')->limit(5)->latest()->get();
            $widgets = UserHasDashboard::all();
            $widgetsBuilder = DashboardBuilder::all();
            $workday = Workdays::where('branch_id', $employee->branch_id)->where('work_shift_id', $employee->work_shift_id)->where('days_id', (date('w') + 1))->first();
    
            // Approval Widget (My Submissions)
            $myLeaveSubmissions = LeaveApplication::join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')->where('employee_id', '=', $user->employee->employee_id)->where('status', '=', 'lpd')->get();
            $myAttendanceSubmissions = Attendance::where('employee_id', '=', $user->employee->employee_id)->where('status', '=', 'apd')->get();
            $myOvertimeSubmissions = Overtime::where('employee_id', '=', $user->employee->employee_id)->where('status', '=', 'opd')->get();
    
            // Approval Widget (Employee Submissions)
            $employeeLeaveSubmissions = $this->leaveApplicationInterface->getAllLeaveApplicationListsAppr($employee->branch_id, $user->employee->employee_id)
                ->join('department', 'employees.department_id', '=', 'department.department_id')
                ->where('department.code', '=', $employee->code)
                ->where('status_codes.code', '=', 'lpd')
                ->get();
    
            $employeeAttendanceSubmissions = $this->manualAttendanceInterface->getAllManualAttendanceAppr($employee->branch_id, $user->employee->employee_id)
                ->where('department.code', '=', $employee->code)
                ->where('attendances.status', '=', 'apd')
                ->get(['attendances.*']);
    
            $employeeOvertimeSubmissions = $this->overtimeInterface->getOvertimeListAppr($employee->employee_id, $user->employee->branch_id)
                ->where('department.code', '=', $employee->code)
                ->where('overtimes.status', '=', 'opd')
                ->get();
    
            return view('dashboard.index', compact('user', 'widgets', 'widgetsBuilder', 'employee', 'workday', 'myLeaveSubmissions', 'myAttendanceSubmissions', 'myOvertimeSubmissions', 'employeeLeaveSubmissions', 'employeeAttendanceSubmissions', 'employeeOvertimeSubmissions', 'employeeName'));
        }


    }

    public function show(Announcement $announcement)
    {
        return view('dashboard.index', [
            "title" => "Single post",
            "content" => $announcement
        ]);
    }

    /**
     * Get announcements data for dashboard.
     *
     * @return void
     */
    public function getAnnouncements()
    {
        define('MAX_NUMBER', 5); //! set maksimal tampilkan 5 announcement di dashboard

        $announcements = $this->announcementRepository->getAnnouncements(MAX_NUMBER)->toArray();
        

        foreach ($announcements["data"] as $index => $announcement) {
            $employee = ($this->employeeRepository->getEmployee($announcement['user']['id']));
            $announcements["data"][$index]['publisher'] = "{$employee['first_name']} {$employee['last_name']}"; // menambahkan nama publisher
            $media_files = array(
                "image"=>null,
                "doc"=>null,
            );

            Carbon::setLocale('en');
            $dateTimeDiff = Carbon::createFromFormat('Y-m-d H:i:s', $announcements['data'][$index]['published_at'])->diffForHumans();
            $announcements['data'][$index]['published_at'] = $dateTimeDiff;
            unset($announcements["data"][$index]['user']); // menghapus data user yang tidak terpakai

            if (!is_null($announcement["media"])) {
                foreach ($announcement["media"] as $i => $media) {
                    $filePath = public_path("uploads/{$media['id']}/{$media['file_name']}"); // mengambil file path
                    $announcements["data"][$index]["media"][$i]["disk"] = "file not found or renamed";
                    
                    if (file_exists($filePath)) {
                        $announcements["data"][$index]["media"][$i]["disk"] = env('APP_URL') . "/uploads/{$media['id']}/{$media['file_name']}";
                    }

                    if($media['collection_name']=="announcement_image"){

                        $media_files["image"] = $announcements["data"][$index]["media"][$i];

                    }else{

                        $media_files["doc"] = $announcements["data"][$index]["media"][$i];

                    }

                    unset($matches);

                    $announcements["data"][$index]["media"][$i]["disk"] = "file not found or renamed";
                    if (file_exists($filePath)) {
                        $announcements["data"][$index]["media"][$i]["disk"] = env('APP_URL') . "/uploads/{$media['id']}/{$media['file_name']}";
                    };
                }
            }

            $announcements["data"][$index]["media"]=$media_files;
            unset($media_files);
        }
        return $announcements;
    }
}
