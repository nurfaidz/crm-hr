<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Interfaces\NotificationsInterface;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class NotificationController extends Controller
{
    private $notificationsInterface;

    public function __construct(NotificationsInterface $notificationsInterface) {
        $this->notificationsInterface = $notificationsInterface;
    }

    public function get_list()
    {
        try {
            $employeeId = Auth::user()->employee->employee_id;
            $notifications = Notification::where('employee_id', $employeeId)->orderBy('send_time', 'desc')->get();
            if (!$notifications->isEmpty()) {
                foreach ($notifications as $notification) {
                    $datetimeDIff =  Carbon::parse(date('Y-m-d H:i:s', strtotime(Carbon::now()->format('Y-m-d H:i:s'))))->diff(date('Y-m-d H:i:s', strtotime($notification->send_time)));

                    if ($datetimeDIff->d == 0) {
                        $hours = $datetimeDIff->h;
                        $minutes = $datetimeDIff->i;
                    } else {
                        $hours = null;
                        $minutes = null;
                    }

                    $data[] = [
                        "id" => $notification->id,
                        "title" => $notification->title,
                        "message" => $notification->message,
                        "notif_type" => $notification->notif_type,
                        "notif_status" => $notification->notif_status,
                        "detail_id" => $notification->detail_id,
                        "isApproval" => $notification->is_approval,
                        "readStamp" => $notification->read_stamp,
                        "sendTime" => $notification->send_time,
                        "dateDiff" => [
                            "h" => $hours,
                            "m" => $minutes,
                        ]
                    ];
                }
                return ResponseFormatter::success($data, "Get List Notification Success", 200);
            } else {
                return ResponseFormatter::error([], "Employee does not have notifications", 400);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function has_read(Request $req)
    {
        try {
            $notification = Notification::find($req->id);
            if ($notification) {
                $notification->update([
                    "read_stamp" => $req->read_stamp
                ]);
                return ResponseFormatter::success([], "Read Notification Success", 200);
            }
            return ResponseFormatter::error([], "Notification not Found", 400);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],  'Something went wrong', 500);
        }
    }

    public function has_read_all()
    {
        try {
            $employeeId = Auth::user()->employee->employee_id;
            $notifications = Notification::where('employee_id', $employeeId)->orderBy('send_time', 'desc')->get();
            if (!$notifications->isEmpty()) {
                foreach ($notifications as $notification) {
                    $notification->update([
                        "read_stamp" => Carbon::now()
                    ]);
                }
                return ResponseFormatter::success([], "Read All Notifications Success", 200);
            } else {
                return ResponseFormatter::error([], "Employee does not have notifications", 400);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function destroy($notification_id)
    {
        try {
            $notification = Notification::where('id', $notification_id)->first();
            if ($notification) {
                $notification->delete();
                return ResponseFormatter::success([], "Delete Notification Success", 200);
            } else {
                return ResponseFormatter::error([], "Notification Not Found", 400);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function destroy_by_employee()
    {
        try {
            $employeeId = Auth::user()->employee->employee_id;
            $notifications = Notification::where('employee_id', $employeeId)->get();
            if (!$notifications->isEmpty()) {
                foreach ($notifications as $notification) {
                    $notification->delete();
                }
                return ResponseFormatter::success([], "Delete All Notifications Success", 200);
            } else {
                return ResponseFormatter::error([], "Employee does not have notifications", 400);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function headerNotification(){
        return $this->notificationsInterface->notificationHeader(Auth::user()->employee->employee_id);
    }

    public function markAllAsRead(){
        return $this->notificationsInterface->readAllNotifications(Auth::user()->employee->employee_id);
    }

    public function clearAllNotification(){
        return $this->notificationsInterface->clearAllNotifications(Auth::user()->employee->employee_id);
    }
}
