<?php

namespace App\Http\Controllers;

use App\Interfaces\EmployeeEducationInterface;
use App\Models\Employee;
use App\Models\EmployeeEducation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeEducationController extends Controller
{
    private EmployeeEducationInterface $employeeEducationInterface;

    public function __construct(
        EmployeeEducationInterface $employeeEducationInterface
    ) {
        $this->employeeEducationInterface = $employeeEducationInterface;
        $this->middleware('permission:self_profile.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:self_profile.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:self_profile.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:self_profile.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing education
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $this->employeeEducationInterface->getEmployeeEducation($id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $educations = EmployeeEducation::all();

        $validate = Validator::make(
            $data,
            [
                'employee_id' => 'required|integer',
                'institution' => 'required',
                'degree' => 'required',
                'major' => 'required',
                'entry_level' => 'required',
                'graduation_year' => 'required|after:entry_level',
                'gpa' => 'required|numeric|max:4',
            ]
        );

        foreach ($educations as $education) {
            if ($data['employee_id'] == $education->employee_id && $data['institution'] == $education->institution && $data['degree'] == $education->degree && $data['major'] == $education->major && $data['entry_level'] == $education->entry_level && $data['graduation_year'] == $education->graduation_year && $data['gpa'] == $education->gpa) {
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

        EmployeeEducation::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Education Data!"
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
        $education = EmployeeEducation::where('id', $id)->first();

        return response()->json($education);
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
        $data = $request->except('_token', 'user_id');
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $data['employee_id'] = $employee->employee_id;
        $educations = EmployeeEducation::all();

        $validate = Validator::make(
            $data,
            [
                'employee_id' => 'required|integer',
                'institution' => 'required',
                'degree' => 'required',
                'major' => 'required',
                'entry_level' => 'required',
                'graduation_year' => 'required|after:entry_level',
                'gpa' => 'required|numeric|max:4',
            ]
        );

        foreach ($educations as $education) {
            if ($education->id != $id) {
                if ($data['employee_id'] == $education->employee_id && $data['institution'] == $education->institution && $data['degree'] == $education->degree && $data['major'] == $education->major && $data['entry_level'] == $education->entry_level && $data['graduation_year'] == $education->graduation_year && $data['gpa'] == $education->gpa) {
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

        $education = EmployeeEducation::where('id', $id)->update($data);

        if ($education) {
            return response()->json(["error" => false, "message" => "Successfully Updated Education Data!"]);
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
            EmployeeEducation::where('id', $id)->delete();
            return response()->json(["error" => false, "message" => "Successfuly Deleted Education Data!"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
