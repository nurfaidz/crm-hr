<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use Exception;
use App\Models\Workdays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\RandomColor;
use App\Models\Days;
use App\Models\WorkShift;

class WorkDaysController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Super Admin|master_workday.list', ['only' => ['index', 'show', 'events']]);
        $this->middleware('role_or_permission:Super Admin|master_workday.create', ['only' => ['create', 'store', 'shifts']]);
        $this->middleware('role_or_permission:Super Admin|master_workday.edit', ['only' => ['edit', 'update', 'shifts']]);
        $this->middleware('role_or_permission:Super Admin|master_workday.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branches::all();
        $workshifts = WorkShift::all();
        $days = Days::all();

        return view('workdays.index', [
            'branches' => $branches,
            'workshifts' => $workshifts,
            'days' => $days
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $workDays = Workdays::all();

        $validate = Validator::make(
            $data,
            [
                'branch_id'     => 'required',
                'work_shift_id' => 'required',
                'days_id'       => 'required',
            ]
        );

        foreach( $workDays as $workDay ) {
            if($data['branch_id'] == $workDay->branch_id && $data['work_shift_id'] == $workDay->work_shift_id && $data['days_id'] == $workDay->days_id){
                return response()->json([
                    "error" => true,
                    "message" => "The data have already been taken"
                ]);
            }
        };

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $data['published_at'] = date('Y-m-d H:i:s');

        Workdays::create($data);
        return response()->json([
            "error" => false,
            "message" => "Successfuly Added a Workdays Data!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function events(Request $request)
    {
        $query = Workdays::leftJoin('branches', 'workdays.branch_id', '=', 'branches.branch_id')->leftJoin('work_shifts', 'workdays.work_shift_id', '=', 'work_shifts.work_shift_id')->leftJoin('days', 'workdays.days_id', '=', 'days.days_id');

        if (request('branch')) {
            $query->where('workdays.branch_id', $request->branch);
        }

        $workdays = $query->get();

        $events = [];

        foreach ($workdays as $workday) {
            $events[] = [
                'id' => $workday->workdays_id,
                'title' => $workday->shift_name,
                'startTime' => $workday->start_time,
                'endTime' => $workday->end_time,
                'daysOfWeek' => [($workday->days_id - 1)],
                'color' => RandomColor::many(1, array('luminosity' => 'light'))[0]
            ];
        }

        return response()->json([
            'events' => $events
        ]);
    }

    public function shifts(Request $request)
    {
        if (!request('branch')) {
            return response()->json([
                "error" => true,
                "message" => "Fail!"
            ]);
        }
        $branch = Branches::where('branch_id', $request->branch)->first();
        $shifts = WorkShift::where('company_id', $branch->company_id)->get();

        return response()->json([
            'shifts' => $shifts
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workdays  = Workdays::where('workdays_id', $id)->first();
        return response()->json($workdays);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);
        $workDays = Workdays::all();

        $validate = Validator::make(
            $data,
            [
                'branch_id'     => 'required',
                'work_shift_id' => 'required',
                'days_id'       => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        foreach ($workDays as $workDay) {
            if($workDay->workdays_id != $id) {
                if ($data['branch_id'] == $workDay->branch_id && $data['work_shift_id'] == $workDay->work_shift_id && $data['days_id'] == $workDay->days_id) {
                    return response()->json([
                        "error" => true,
                        "message" => "The data have already been taken"
                    ]);
                }
            }
        };

        $workdays = Workdays::where('workdays_id', $id);
        $workdays->update($data);
        return response()->json(["error" => false, "message" => "Successfuly Updated a Workday Data!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Workdays::where('workdays_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
        return response()->json(["error" => false, "message" => "Successfuly Deleted a Workday Data! !"]);
    }
}
