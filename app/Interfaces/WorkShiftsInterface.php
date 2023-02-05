<?php

namespace App\Interfaces;

interface WorkShiftsInterface
{
    public function getWorkingHours($workshiftId);
    public function getWorkShiftByID($workshiftId);
}
