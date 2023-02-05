<?php

namespace App\Interfaces;

interface WorkDaysInterface
{
    public function getWorkingDays($branchID, $workshiftID);
}
