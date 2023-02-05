<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WorkShift;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkShiftController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Super Admin|master_workshift.list', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Super Admin|master_workshift.create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Super Admin|master_workshift.edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Super Admin|master_workshift.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workShifts = WorkShift::leftJoin('companies', 'work_shifts.company_id', '=', 'companies.company_id')->get();
        $holdings = Company::all();

        if ($request->ajax()) {
            return datatables()->of($workShifts)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<button type="submit" title="Edit" data-toggle="modal" data-target="#modal-form" data-id="' . $data->work_shift_id . '" class="edit btn btn-icon btn-success mx-auto"  id="edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/work-shifts/delete/' . $data->work_shift_id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn btn-icon btn-danger mx-auto" onclick="sweetConfirm(' . $data->work_shift_id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('work-shifts.index', [
            'holdings' => $holdings,
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
        $data = $request->all();

        $validate = Validator::make(
            $data,
            [
                'company_id' => 'required',
                'shift_name' => 'required|unique:work_shifts',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'max_arrival' => 'required|after_or_equal:start_time|before:end_time'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        WorkShift::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Work Shift Data!"
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('work_shift_id' => $id);
        $post  = WorkShift::leftJoin('companies', 'work_shifts.company_id', '=', 'companies.company_id')->where($where)->first();

        return response()->json($post);
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

        $validate = Validator::make(
            $data,
            [
                'company_id' => 'required',
                'shift_name' => [
                    'required',
                    Rule::unique('work_shifts')->ignore($id, 'work_shift_id')
                ],
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'max_arrival' => 'required|after_or_equal:start_time|before:end_time'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $workShift = WorkShift::where('work_shift_id', $id);
        $workShift->update($data);

        if ($workShift) {
            return response()->json(["error" => false, "message" => "Successfully Updated Work Shift Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
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
            WorkShift::where('work_shift_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfuly Deleted Work Shift Data!"]);
    }
}