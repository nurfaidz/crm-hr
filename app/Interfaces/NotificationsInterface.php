<?php

namespace App\Interfaces;

interface NotificationsInterface{
    public function notificationHeader(int $employeeId) : object;
    public function getAllNotifications(int $employeeId, int $maxNum);
    public function countNewNotifications(int $employeeId) : int;
    public function readAllNotifications(int $employeeId);
    public function clearAllNotifications(int $employeeId);
}