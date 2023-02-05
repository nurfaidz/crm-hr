<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeEmergencyContact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeEmergencyContactController extends Controller
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

        $validate = Validator::make(
            $data,
            [
                'employee_id' => 'required|integer',
                'name' => 'required|max:191',
                'connection' => 'required|max:191',
                'contact' => 'required|max:14|unique:employee_emergency_contacts',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        EmployeeEmergencyContact::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Emergency Contact Data!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeEmergencyContact  $employeeEmergencyContact
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeEmergencyContact $employeeEmergencyContact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeEmergencyContact  $employeeEmergencyContact
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeEmergencyContact $employeeEmergencyContact)
    {
        try {
            return response()->json($employeeEmergencyContact);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeEmergencyContact  $employeeEmergencyContact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeEmergencyContact $employeeEmergencyContact)
    {
        $data = $request->except('_token');
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $data['employee_id'] = $employee->employee_id;

        $validation = [
            'employee_id' => 'required|integer',
            'name' => 'required|max:191',
            'connection' => 'required|max:191',
        ];
        if ($data['contact'] != $employeeEmergencyContact->contact) {
            $validation['contact'] = 'required|max:14|unique:employee_emergency_contacts';
        }

        $validate = Validator::make(
            $data,
            $validation
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $experience = $employeeEmergencyContact->update($data);

        if ($experience) {
            return response()->json(["error" => false, "message" => "Successfully Updated Emergency Contact Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeEmergencyContact  $employeeEmergencyContact
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeEmergencyContact $employeeEmergencyContact)
    {
        try {
            $employeeEmergencyContact->delete();
            return response()->json(["error" => false, "message" => "Successfuly Deleted Experience Data!"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
