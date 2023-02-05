<?php

namespace App\Repositories;

use App\Interfaces\LeavePeriodInterface;
use App\Models\LeavePeriod;
use Carbon\Carbon;

class LeavePeriodRepository implements LeavePeriodInterface
{

    // Fetch all Period Leave data from ORM
    public function getAllPeriodLeave()
    {
        return LeavePeriod::all();
    }

    public function firstFillingData()
    {
        $data = (object)[
            "from_date" => "2022-01-01",
            "to_date" => "2022-12-31",
            "limit" => 12,
            "year" => "same",
        ];
        LeavePeriod::create((array)$data);
    }

    public function postLeavePeriod($data)
    {
        LeavePeriod::create([
            'year' => $data['year'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'limit' => $data['limit']
        ]);
    }

    public function putLeavePeriod($id, $data)
    {
        LeavePeriod::where('leave_period_id', $id)->update([
            'year' => $data['year'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'limit' => $data['limit']
        ]);
    }
    
    public function getLeavePeriodOnThisYear(){
        $now = Carbon::now();
        return LeavePeriod::whereDate('from_date', '<= ',$now)
            ->whereDate('to_date', '>= ', $now)
            ->get();
    }
}
