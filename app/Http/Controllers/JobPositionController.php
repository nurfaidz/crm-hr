<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobClass;
use App\Models\JobPosition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobPositionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:job_position.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:job_position.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:job_position.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:job_position.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobPositions = JobPosition::join('department', 'job_positions.department_id', '=', 'department.department_id')
            ->join('branches', 'department.department_branch_id', '=', 'branches.branch_id')
            ->join('job_classes', 'job_positions.job_class_id', '=', 'job_classes.job_class_id');
        $departments = Department::all();
        $jobClasses = JobClass::all();

        if ($request->ajax()) {
            return datatables()->of($jobPositions)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<button type="button" title="Edit" id="edit" data-toggle="modal" data-target="#modal-form" data-id="' . $data->job_position_id . '" class="edit btn btn-icon btn-success mx-auto"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/job-positions/delete/' . $data->job_position_id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn btn-icon btn-danger mx-auto" onclick="sweetConfirm(' . $data->job_position_id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('job-positions.index', compact('departments', 'jobClasses'));
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
                'job_position' => 'required',
                'department_id' => 'required',
                'job_class_id' => 'required'
            ]
        );

        $jobPositions = JobPosition::where('department_id', $request->department_id)->get();

        foreach ($jobPositions as $jobPosition) {
            if (strtolower($request->job_position) == strtolower($jobPosition->job_position)) {
                return response()->json([
                    "error" => true,
                    "message" => "The job position have already been taken"
                ]);
            }
        }

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        JobPosition::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Job Position Data!"
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
        $where = array('job_position_id' => $id);
        $post  = JobPosition::where($where)->first();

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
        $jobPosition = JobPosition::where('job_position_id', $id)->first();
        if (!$jobPosition) {
            abort(404);
        }

        $data = $request->except(['_token']);

        $validate = Validator::make(
            $data,
            [
                'job_position' => 'required',
                'department_id' => 'required',
                'job_class_id' => 'required'
            ]
        );

        $jobPositions = JobPosition::where('department_id', $request->department_id)->get();

        if ($request->job_position != $jobPosition->job_position || $request->department_id != $jobPosition->department_id) {
            foreach ($jobPositions as $jobPosition) {
                if (strtolower($request->job_position) == strtolower($jobPosition->job_position)) {
                    return response()->json([
                        "error" => true,
                        "message" => "The job position have already been taken"
                    ]);
                }
            }
        }

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $jobPosition = JobPosition::where('job_position_id', $id)->update($data);

        if ($jobPosition) {
            return response()->json(["error" => false, "message" => "Successfully Updated Job Position Data!"]);
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
            JobPosition::where('job_position_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfuly Deleted Job Position Data!"]);
    }
}
