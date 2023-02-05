<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeExperienceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:self_profile.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:self_profile.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:self_profile.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:self_profile.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $data['employee_id'] = $employee->employee_id;
        $experiences = EmployeeExperience::all();

        $validate = Validator::make(
            $data,
            [
                'employee_id' => 'required|integer',
                'corporate' => 'required|max:191',
                'position' => 'required|max:191',
                'years' => 'required|integer|max:100',
                'description' => 'required',
            ]
        );

        foreach ($experiences as $experience) {
            if ($data['employee_id'] == $experience->employee_id && $data['corporate'] == $experience->corporate && $data['position'] == $experience->position && $data['years'] == $experience->years && $data['description'] == $experience->description) {
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

        EmployeeExperience::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Experience Data!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeExperience  $employeeExperience
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeExperience $employeeExperience)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeExperience  $employeeExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeExperience $employeeExperience)
    {
        try {
            return response()->json($employeeExperience);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeExperience  $employeeExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeExperience $employeeExperience)
    {
        $data = $request->except('_token');
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $data['employee_id'] = $employee->employee_id;
        $experiences = EmployeeExperience::all();

        $validate = Validator::make(
            $data,
            [
                'employee_id' => 'required|integer',
                'corporate' => 'required|max:191',
                'position' => 'required|max:191',
                'years' => 'required|integer|max:100',
                'description' => 'required',
            ]
        );

        foreach ($experiences as $experience) {
            if ($experience->id != $employeeExperience->id) {
                if ($data['employee_id'] == $experience->employee_id && $data['corporate'] == $experience->corporate && $data['position'] == $experience->position && $data['years'] == $experience->years && $data['description'] == $experience->description) {
                    return response()->json([
                        "error" => true,
                        "message" => "The data have already been taken"
                    ]);
                }
            }
        };

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $experience = $employeeExperience->update($data);

        if ($experience) {
            return response()->json(["error" => false, "message" => "Successfully Updated Experience Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeExperience  $employeeExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeExperience $employeeExperience)
    {
        try {
            $employeeExperience->delete();
            return response()->json(["error" => false, "message" => "Successfuly Deleted Experience Data!"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
