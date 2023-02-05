<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSkill;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EmployeeSkillController extends Controller
{
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
        $requestData                = $request->all();
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $requestData['employee_id'] = $employee->employee_id;

        $validate = Validator::make(
            $requestData,
            [
                'employee_id' => 'required|integer',
                'skill_name'  => 'required',
                'issued_by'   => 'required',
                'issued_date' => 'required',
                'tags'        => 'required'
            ],
            [
                'skill_name.required'  => 'The name field is required.',
                'issued_by.required'   => 'The issuing organization field is required.',
                'issued_date.required' => 'The expiration date field is required.',
                'tags.required'        => 'The skills field is required.'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        EmployeeSkill::create($requestData);

        return response()->json([
            "error"   => false,
            "message" => "Successfully Added Employee Skill!"
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
        try {
            $skills = EmployeeSkill::find($id);
            return response()->json($skills);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
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
        $requestData = $request->except('_token', 'user_id');
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $requestData['employee_id'] = $employee->employee_id;

        $validate = Validator::make(
            $requestData,
            [
                'employee_id' => 'required|integer',
                'skill_name'  => 'required',
                'issued_by'   => 'required',
                'issued_date' => 'required',
                'tags'        => 'required'
            ],
            [
                'skill_name.required'  => 'The name field is required.',
                'issued_by.required'   => 'The issuing organization field is required.',
                'issued_date.required' => 'The expiration date field is required.',
                'tags.required'        => 'The skills field is required.'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $skill = EmployeeSkill::where('id', $id)->update($requestData);

        if ($skill) {
            return response()->json(["error" => false, "message" => "Successfully Updated Employee Skill!"]);
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
            EmployeeSkill::where('id', $id)->delete();
            return response()->json(["error" => false, "message" => "Successfuly Deleted Employee Skill!"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
