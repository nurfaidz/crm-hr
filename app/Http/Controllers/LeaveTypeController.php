<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:leave_type.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:leave_type.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:leave_type.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:leave_type.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::all();

        if ($request->ajax()) {
            return datatables()->of($leaveTypes)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    return '<button type="submit" title="Edit" data-toggle="modal" data-target="#modal-form" data-id="' . $data->leave_type_id . '" class="edit btn btn-icon btn-success mx-auto" id="edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/leave-types/delete/' . $data->leave_type_id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn btn-icon btn-danger mx-auto" onclick="sweetConfirm(' . $data->leave_type_id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('leave-types.index');
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
                'leave_type_name' => 'required|unique:leave_types',
                'type' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        LeaveType::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Leave Type Data!"
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
        $where = array('leave_type_id' => $id);
        $post  = LeaveType::where($where)->first();

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
                'leave_type_name' => [
                    'required',
                    Rule::unique('leave_types')->ignore($id, 'leave_type_id')
                ],
                'type' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $leaveType = LeaveType::where('leave_type_id', $id);
        $leaveType->update($data);

        if ($leaveType) {
            return response()->json(["error" => false, "message" => "Successfully Updated Leave Type Data!"]);
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
            LeaveType::where('leave_type_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfully Deleted Leave Type Data!"]);
    }
}