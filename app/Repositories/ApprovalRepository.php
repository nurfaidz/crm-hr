<?php

namespace App\Repositories;

use App\Interfaces\ApprovalInterface;
use App\Models\Attendance;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Exception;

class ApprovalRepository implements ApprovalInterface
{
    public function leaveApprovalStatistics($user_id)
    {
        try {
            $response = [
                'leaveApplications' => $this->getLeaveApplications($user_id)
            ];

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLeaveApplications($user_id)
    {
        $leaveApplications = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')->join('users', 'employees.user_id', '=', 'users.id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')->where('employees.user_id', '=', $user_id)->get(['leave_application_id', 'employees.employee_id', 'first_name', 'last_name', 'job_positions.job_position', 'leave_type_name AS leave_type', 'application_from_date', 'application_to_date', 'application_date', 'number_of_day AS total_leave_days', 'purpose AS notes', 'short AS status', 'sick_letter AS files']);

        $leaveApplications->each(function($leaveApplication) {
            $leaveApplication['employee'] = [
                'id' => $leaveApplication['employee_id'],
                'name' => $leaveApplication['first_name'] . ' ' . $leaveApplication['last_name'],
                'job_position' => $leaveApplication['job_position']
            ];

            $leaveApplication['leave_application_date'] = [
                'from' => $leaveApplication['application_from_date'],
                'to' => $leaveApplication['application_to_date']
            ];

            $leaveApplicationDate = explode('-', $leaveApplication['application_date']);
            $leaveApplicationDate = $leaveApplicationDate[2] . '/' . $leaveApplicationDate[1] . '/' . $leaveApplicationDate[0];
            $leaveApplication['application_date'] = $leaveApplicationDate;

            if (str_contains($leaveApplication['files'], '|')) {
                $url = explode('/', $leaveApplication['files']);
                $image = explode('|', $url[1]);
                $images = [];

                for ($i = 0; $i < sizeof($image); $i++) {
                    array_push($images, [
                        'link' => env('APP_URL') . '/uploads/'. $url[0] . '/' . $image[$i],
                        'filename' => basename($image[$i])
                    ]);
                }

                $leaveApplication['files'] = $images;
            } else {
                $leaveApplication['files'] = [
                    'link' => env('APP_URL') . '/' . $leaveApplication['files'],
                    'filename' => basename($leaveApplication['files'])
                ];
            }

            unset($leaveApplication->employee_id, $leaveApplication->first_name, $leaveApplication->last_name, $leaveApplication->job_position, $leaveApplication->application_from_date, $leaveApplication->application_to_date);
        });

        return $leaveApplications;
    }

    public function attendanceApprovalStatistics($user_id)
    {
        try {
            $response = [
                'manualAttendances' => $this->getManualAttendances($user_id)
            ];

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getManualAttendances($user_id)
    {
        $manualAttendances = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')->join('users', 'employees.user_id', '=', 'users.id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('status_codes', 'attendances.status', '=', 'status_codes.code')->where('employees.user_id', '=', $user_id)->where('image_manual_attendance', '!=', null)->get(['attendances.id AS attendance_id', 'employees.employee_id', 'first_name', 'last_name', 'job_positions.job_position', 'date AS attendance_date', 'check_in', 'check_out', 'working_hour', 'note AS notes', 'short AS status', 'status_date', 'image_manual_attendance AS files', 'cancel_reason', 'reject_reason']);

        $manualAttendances->each(function($manualAttendance) {
            $manualAttendance['employee'] = [
                'id' => $manualAttendance['employee_id'],
                'name' => $manualAttendance['first_name'] . ' ' . $manualAttendance['last_name'],
                'job_position' => $manualAttendance['job_position'],
            ];

            $CheckInDate = explode('-', $manualAttendance['check_in']);
            $CheckInyear = explode(' ', $CheckInDate[2]);
            $manualAttendance['check_in_date'] = $CheckInDate[0] . '/' . $CheckInDate[1] . '/' . $CheckInyear[0];

            $CheckOutDate = explode('-', $manualAttendance['check_out']);
            $CheckOutyear = explode(' ', $CheckOutDate[2]);
            $manualAttendance['check_out_date'] = $CheckOutDate[0] . '/' . $CheckOutDate[1] . '/' . $CheckOutyear[0];

            $separateAttendanceDate = explode('-', $manualAttendance['attendance_date']);
            $manualAttendance['attendance_date'] = $separateAttendanceDate[2] . '/' . $separateAttendanceDate[1] . '/' . $separateAttendanceDate[0];

            $manualAttendance['attendance_time'] = [
                'check_in' => Carbon::parse($manualAttendance['check_in'])->format('H:i A'),
                'check_out' => Carbon::parse($manualAttendance['check_out'])->format('H:i A')
            ];

            $separateTime = explode(':', $manualAttendance['working_hour']);
            $manualAttendance['working_hour'] = [
                'h' => (int)$separateTime[0],
                'm' => (int)$separateTime[1]
            ];

            $separateStatusDate = explode('-', $manualAttendance['status_date']);
            $manualAttendance['status_date'] = $separateStatusDate[2] . '/' . $separateStatusDate[1] . '/' . $separateStatusDate[0];

            $manualAttendance['files'] = [
                'link' => $manualAttendance['files'],
                'filename' => basename($manualAttendance['files'])
            ];

            unset($manualAttendance->employee_id, $manualAttendance->first_name, $manualAttendance->last_name, $manualAttendance->job_position, $manualAttendance->check_in, $manualAttendance->check_out);
        });

        return $manualAttendances;
    }
}