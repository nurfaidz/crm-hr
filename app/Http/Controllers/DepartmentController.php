<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:department_company.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:department_company.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:department_company.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:department_company.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(config('auth.defaults.guard'));
        // dd(
        //     Auth::user()->getAllPermissions()->toArray(),
        //     Auth::user()->can('department_company.list'),
        //     Auth::user()->hasPermissionTo('department_company.list'),
        // );
        $departments = Department::latest()->get();
        $branches = Branches::all();

        return view('department.index', compact('departments', 'branches'));
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
        $data = $request->only('code', 'department_name', 'department_branch_id', 'manager');
        $rules = [
            'code' => 'required|max:6',
            'department_name' => 'required|max:150',
            'department_branch_id' => 'required',
            'manager' => 'required'
        ];

        $validatedData = Validator::make($data, $rules);
        $dep = Department::count();
        $departments = Department::where('department_branch_id', $request->department_branch_id)->get();

        foreach ($departments as $department) {
            if ($data['code'] == $department->code && $data['department_name'] == $department->department_name) {
                return response()->json([
                    "error" => true,
                    "message" => "The data have already been taken"
                ]);
            }

            if ($data['code'] == $department->code) {
                return response()->json([
                    "error" => true,
                    "message" => "The code have already been taken"
                ]);
            }

            if ($data['department_name'] == $department->department_name) {
                return response()->json([
                    "error" => true,
                    "message" => "The name have already been taken"
                ]);
            }
        };

        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        if($dep < 24){            
            $result = Department::create([
                'code' => $request->code,
                'department_name' => $request->department_name,
                'department_branch_id' => $request->department_branch_id,
                'manager' => $request->manager
            ]);
            
            if ($result->department_id > 0) {
                return response()->json(["error" => false, "message" => "Successfully Added SBU!"], 201);
            }
        }else{
            return response()->json(["error" => true, "message" => "Reaching Data Limit!"], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $branch = Branches::find($department->department_branch_id);
        return response()->json([
            'department' => $department,
            'branch' => $branch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $data = $request->only('code', 'department_name', 'department_branch_id', 'manager');
        $rules = [
            'department_branch_id' => 'required',
            'manager'              => 'required',
            'code'                 => 'required|max:6',
            'department_name'      => 'required|max:150'
        ];

        $departments = Department::where('department_branch_id', $request->department_branch_id)->get();
        if ($request->code != $department->code) {
            foreach ($departments as $department) {
                if ($data['code'] == $department->code && $data['department_name'] == $department->department_name) {
                    return response()->json([
                        "error" => true,
                        "message" => "The data have already been taken"
                    ]);
                }

                if ($data['code'] == $department->code) {
                    return response()->json([
                        "error" => true,
                        "message" => "The code have already been taken"
                    ]);
                }
            };
        }

        if ($request->department_name != $department->department_name) {
            foreach ($departments as $department) {
                if ($data['department_name'] == $department->department_name) {
                    return response()->json([
                        "error" => true,
                        "message" => "The name have already been taken"
                    ]);
                }
            }
        }

        $validatedData = Validator::make($data, $rules);

        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        $result = Department::where('department_id', $department->department_id)
            ->update([
                'code' => $request->code,
                'department_name' => $request->department_name,
                'department_branch_id' => $request->department_branch_id,
                'manager' => $request->manager
            ]);

        if ($result > 0) {
            return response()->json(["error" => false, "message" => "Successfully Updated SBU!"]);
        }

        return response()->json(["error" => true, "message" => "Unsuccessfully Updated SBU!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        Department::destroy($department->department_id);

        return response()->json(["error" => false, "message" => "Successfully Deleted SBU!"]);
    }

    public function get_department_ajax(Request $request)
    {
        $departments = Department::leftjoin('employees', 'department.manager', '=', 'employees.employee_id')
        ->leftjoin('branches', 'department.department_branch_id', '=', 'branches.branch_id' )
            ->get([
                'department.department_id',
                'department.code',
                'department.department_name',
                'department_branch_id',
                'branches.branch_id',
                'branches.branch_name',
                'employees.first_name',
                'employees.last_name'
            ]);

        return Datatables::of($departments)
            ->addIndexColumn()
            // ->addColumn('branch_name', function (Department $department) {
            //     return $department->branches->branch_name;
            // })
            ->addColumn('action', function ($row) {
                $actionBtn = '<button title="Edit" type="button" data-toggle="modal" data-target="#modal-form" data-id="' . $row->department_id . '" class="edit btn btn-icon btn-success mx-auto" id="edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/departments/delete/' . $row->department_id . '" method="post" title="Delete"><button title="Delete" type="submit"  class="btn btn-icon btn-danger mx-auto" onclick="sweetConfirm(' . $row->department_id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function select_department_ajax()
    {
        $list_all = Department::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = [
                "id" => $item->department_id,
                "text" => $item->department_name,
            ];
        }
        return response()->json(["error" => false, "data" => $select]);
    }

    public function select_department_ajax_by_branch($branch_id)
    {
        // var_dump(($branch_id));
        $list_all = Department::where('department_branch_id', $branch_id)->get();
        // $list_all = Department::all();
        // var_dump(($list_all));
        $select = [];

        foreach ($list_all as $item) {
            $select[] = [
                "id" => $item->department_id,
                "text" => $item->department_name,
            ];
        }
        return response()->json(["error" => false, "data" => $select]);
    }

    public function manager_list(Request $request)
    {
        // $results = Employee::where('branch_id', $branch_id)->get();
        // return response()->json($results);
        $list_all = Employee::where('branch_id', $request->branch_id)->get();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = [
                "manager_id" => $item->employee_id,
                "manager" => $item->first_name . ' ' . $item->last_name,
            ];
        }
        return response()->json($select);
    }
}
