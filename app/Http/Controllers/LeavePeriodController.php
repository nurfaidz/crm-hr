<?php

namespace App\Http\Controllers;

use App\Models\LeavePeriod;
use App\Http\Controllers\Controller;
use App\Interfaces\LeavePeriodInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeavePeriodController extends Controller
{
    private LeavePeriodInterface $leavePeriodInterface;

    public function __construct(LeavePeriodInterface $leavePeriodInterface)
    {
        $this->leavePeriodInterface = $leavePeriodInterface;
        $this->middleware('role_or_permission:Super Admin|leave_period.list', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Super Admin|leave_period.create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Super Admin|leave_period.edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Super Admin|leave_period.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leave_periods = $this->leavePeriodInterface->getAllPeriodLeave();

        if (empty($leave_periods)) {
            $this->leavePeriodInterface->firstFillingData();
        }

        if ($request->ajax()) {
            return datatables()->of($leave_periods)
                ->addIndexColumn()
                ->addColumn('action', function ($leave_period) {
                    $button = '<button type="button" title="Edit" id="edit" data-toggle="modal" data-target="#modal-form" data-id="' . $leave_period->leave_period_id . '" class="edit btn btn-icon btn-success mx-auto"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('leave-periods.index', ['leave_periods' => $leave_periods]);
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
     * @param  \App\Http\Requests\StoreLeavePeriodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leavePeriods = LeavePeriod::all();

        $data = $request->only('year', 'rangeDate', 'limit');
        $data['from_date'] = substr($request->rangeDate, 0, 10);
        $data['to_date'] = substr($request->rangeDate, 14);
        $data['expired_date'] = date('Y-m-d', strtotime($data['to_date'] . '6 months'));

        foreach ($leavePeriods as $leavePeriod) {
            $validatedData = Validator::make($data, [
                'year' => 'required',
                'rangeDate' => 'required|string|min:24|max:24',
                'limit' => 'required|integer',
                'from_date' => "required|date",
                'to_date' => "required|date|after:from_date"
            ]);

            if ($validatedData->fails()) {
                $messages = $validatedData->getMessageBag();
                return response()->json(['error' => true, 'message' => $messages], 400);
            }
        }

        LeavePeriod::create($data);

        return response()->json(["error" => false, "message" => "Successfully Added Leave Period!"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeavePeriod  $leavePeriod
     * @return \Illuminate\Http\Response
     */
    public function show(LeavePeriod $leavePeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeavePeriod  $leavePeriod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('leave_period_id' => $id);
        $post  = LeavePeriod::where($where)->first();

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeavePeriodRequest  $request
     * @param  \App\Models\LeavePeriod  $leavePeriod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $leave = LeavePeriod::where('leave_period_id', $id)->first();
        $leavePeriods = LeavePeriod::all();

        $data = $request->only('year', 'rangeDate', 'limit');
        $data['from_date'] = substr($request->rangeDate, 0, 10);
        $data['to_date'] = substr($request->rangeDate, 14);
        $data['expired_date'] = date('Y-m-d', strtotime($data['to_date'] . '6 months'));

        $rules = [
            'year' => 'required',
            'rangeDate' => 'required|string|min:24|max:24',
            'limit' => 'required|integer',
        ];
        foreach ($leavePeriods as $row) {
            if ($data['rangeDate'] !== $leave->from_date . ' to ' . $leave->to_date) {
                if ($row->leave_period_id != $id) {
                    if ($data['from_date'] > $row->to_date && $data['to_date'] > $row->to_date) {
                        $rules['from_date'] = "required|date|after:$row->to_date";
                        $rules['to_date'] = "required|date|after:from_date|after:$row->to_date";
                    } else if ($data['from_date'] < $row->from_date && $data['to_date'] < $row->from_date) {
                        $rules['from_date'] = "required|date|before:$row->from_date";
                        $rules['to_date'] = "required|date|after:from_date|before:$row->from_date";
                    } else {
                        $rules['from_date'] = "required|date|after:$row->to_date";
                        $rules['to_date'] = "required|date|after:from_date|after:$row->to_date";
                    }
                }
            }

            $validatedData = Validator::make($data, $rules);

            if ($validatedData->fails()) {
                $messages = $validatedData->getMessageBag();
                return response()->json(['error' => true, 'message' => $messages], 400);
            }
        }

        LeavePeriod::where('leave_period_id', $id)->update([
            'year' => $data['year'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'limit' => $data['limit'],
            'expired_date' => $data['expired_date']
        ]);

        return response()->json(["error" => false, "message" => "Successfully Updated Leave Period!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeavePeriod  $leavePeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeavePeriod $leavePeriod)
    {
        //
    }
}
