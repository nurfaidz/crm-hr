<?php

namespace App\Repositories;

use App\Helpers\ResponseFormatter;
use App\Interfaces\NotificationsInterface;
use App\Models\Notification;
use Carbon\Carbon;
use Exception;

class NotificationsRepository implements NotificationsInterface
{
    public function notificationHeader(int $employeeId) : object
    {
        $list = $this->getAllNotifications($employeeId, 15);
        $countRead = $this->countNewNotifications($employeeId);
        
        return (object)[
            'list' => $list,
            'countRead' => $countRead
        ];
    }

    public function readAllNotifications(int $employeeId)
    {
        try{
            $notifications = Notification::where('employee_id', $employeeId)
                ->where('read_stamp', '=', null)
                ->get();

            foreach ($notifications as $notification) {
                $notification->update([
                    "read_stamp" => Carbon::now()
                ]);
            }
            return ResponseFormatter::success([], "Read All Notifications Success", 200);
        }
        catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function clearAllNotifications(int $employeeId){
        try {
            Notification::where('employee_id', $employeeId)->delete();

            return ResponseFormatter::success([], "Read All Notifications Success", 200);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function getAllNotifications(int $employeeId, int $maxNum)
    {
        try {
            $notifications = Notification::where('employee_id', $employeeId)->orderBy('send_time', 'desc')->take($maxNum)->get();
            if (!$notifications->isEmpty()) {
                foreach ($notifications as $notification) {
                    Carbon::setLocale('en');
                    $dateTimeDiff = Carbon::createFromFormat('Y-m-d H:i:s', $notification->send_time)->diffForHumans();

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
                        "dateDiff" => $dateTimeDiff
                    ];
                }
                return $data;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function countNewNotifications(int $employeeId) : int
    {
        $notification = Notification::where('employee_id', $employeeId)
            ->where('read_stamp', '=', null)->count();

        return $notification;
    }
}