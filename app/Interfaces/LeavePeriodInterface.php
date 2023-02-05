<?php

namespace App\Interfaces;

interface LeavePeriodInterface
{
    public function getAllPeriodLeave();
    public function firstFillingData();
    public function postLeavePeriod($data);
    public function putLeavePeriod($id, $data);
    public function getLeavePeriodOnThisYear();
}
