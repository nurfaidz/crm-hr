<?php

namespace App\Http\Controllers;

use App\Interfaces\EmployeeEducationInterface;
use App\Interfaces\EmployeeInterface;
use App\Models\Bank;
use App\Models\Branches;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\TempEmployee;
use App\Models\EmployeeEducation;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeExperience;
use App\Models\EmployeeSkill;
use App\Models\EmploymentStatus;
use App\Models\JobClass;
use App\Models\JobPosition;
use App\Models\MaritalStatus;
use App\Models\ModelHasRoles;
use App\Models\EmployeeBalance;
use App\Models\Religion;
use App\Models\Role;
use App\Models\SalaryType;
use App\Models\User;
use App\Models\Vaccine;
use App\Models\WorkShift;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Requests\EmployeeStoreRequest;

class EmployeeController extends Controller
{
    private EmployeeInterface $employeeInterface;
    private EmployeeEducationInterface $employeeEducationInterface;

    public function __construct(EmployeeInterface $employeeInterface, EmployeeEducationInterface $employeeEducationInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->employeeEducationInterface = $employeeEducationInterface;

        $this->middleware('permission:master_employee.list', ['only' => ['index']]);
        $this->middleware('permission:master_employee.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:master_employee.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:master_employee.delete', ['only' => ['destroy']]);
        $this->middleware('permission:self_profile.list', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = Employee::join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->join('model_has_roles', 'employees.user_id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('religions', 'employees.religion_id', '=', 'religions.religion_id')
            ->join('employment_statuses', 'employees.employment_status_id', '=', 'employment_statuses.employment_status_id')
            ->where('date_of_leaving', '=', null);

        if ($request->ajax()) {
            return datatables()->of($employees)
                ->addIndexColumn()
                ->addColumn('employee_image', function ($data) {
                    return $this->employeeInterface->getEmployeePicture($data['user_id']);
                })
                ->addColumn('job_level', function ($data) {
                    $userId = $data['user_id'];
                    return User::where('id', $userId)->first()->getRoleNames()->first();
                })
                ->addColumn('action', function ($data) {
                    return '<form id="form_delete_data" style="display:inline" class="" action="/employees/delete/' . $data->employee_id . '" method="POST" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn p-0" onclick="sweetConfirm(' . $data->employee_id . ')"><img src="./img/icons/trash.svg" alt="Delete"></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('employees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::all();
        $maritalStatuses = MaritalStatus::all();
        $religions = Religion::all();
        $companies = Company::all();
        $departments = Department::all();
        $branches = Branches::all();
        $employmentStatuses = EmploymentStatus::all();
        $jobClasses = JobClass::all();
        $jobPositions = JobPosition::all();
        $workShifts = WorkShift::all();
        $salaryTypes = SalaryType::all();
        $banks = Bank::all();
        $allEmail = User::get('email');
        $allNik = Employee::get('nik');

        if (request('company')) {
            $branches = Branches::where('company_id', $request->company)->get();
            $workshifts = WorkShift::where('company_id', $request->company)->get();
            return response()->json(['branches' => $branches, 'workshifts' => $workshifts]);
        }

        return view('employees.create', compact('roles', 'maritalStatuses', 'religions', 'companies', 'departments', 'branches', 'employmentStatuses', 'jobClasses', 'jobPositions', 'workShifts', 'salaryTypes', 'banks', 'allEmail', 'allNik'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeStoreRequest $request)
    {
        $emp = Employee::count();
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        
        $employeeData = $data;
        
        $getDate = explode('-', $employeeData['date_of_birth']);
        $year = substr($getDate[0], -2);
        $month = $getDate[1];
        $firstLetterName = substr($employeeData['first_name'], 0, 1);
        $getUser = User::orderBy('id', 'DESC')->limit(1)->first();
        $getUserId = str_pad($getUser['id'], 4, '0', STR_PAD_LEFT);
        $employeeData['nip'] = $month . $year . $getUserId . $firstLetterName;
        
        $fix = 0;
        if($request->salary_type_id == 1){
            $clean = preg_replace("/[^0-9]/", "", $request->basic_salary);
            $fix = $clean;
        }else if($request->salary_type_id == 2){
            $clean = preg_replace("/[^0-9]/", "", $request->basic_salary);
            $fix = $clean / 12;
        }else if($request->salary_type_id == 4){
            $clean = preg_replace("/[^0-9]/", "", $request->basic_salary);
            $fix = $clean * 30;
        }else if($request->salary_type_id == 5){
            $clean = preg_replace("/[^0-9]/", "", $request->basic_salary);
            $fix = $clean * 4;
        }
        
        $test = User::where('email', '=', $request->email);
        $test1 = Employee::where('nik', '=', $request->nik);
        if($test->exists() || $test1->exists())
        {
            return response()->json(["error" => true, "message" => "Email or Residential Identity Card already taken"]);
        } 

        if($emp > 5)
        {
            return response()->json(['error' => true, 'message' => "Reaching Data Limit!"]);
        }else{
            $userData = $this->employeeInterface->createUser($data);
            $userData->assignRole($data['role_id']);
            $employeeData['user_id'] = $userData->id;
            $createEmployee = $this->employeeInterface->createEmployee($employeeData);
            
            $data = EmployeeBalance::create([
                "employee_id" => $createEmployee->employee_id,
                "total_balance" => (int)$fix,
            ]);
    
            return response()->json(['message' => 'Successfully Added Employee Data!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $holdings = Company::all();
        $roles = Role::all();
        $religions = Religion::all();
        $employmentStatuses = EmploymentStatus::all();
        $marital_statuses  = MaritalStatus::all();

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $user = User::where('id', $employee->user_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $vaccine = Vaccine::where('employee_id', $employee->employee_id)->first();
        $profile = $this->employeeInterface->getEmployeeNameById($user->id);
        $profilePic = $this->employeeInterface->getEmployeePicture($user->id);

        $educations = EmployeeEducation::where('employee_id', $employee->employee_id)->get();
        $experiences = EmployeeExperience::where('employee_id', $employee->employee_id)->get();
        $skills = EmployeeSkill::where('employee_id', $employee->employee_id)->get();
        $emergencyContacts = EmployeeEmergencyContact::where('employee_id', $employee->employee_id)->get();

        if (request('company')) {
            $branches = Branches::where('company_id', $request->company)->get();
            return response()->json(['branches' => $branches, 'branch' => $employee->branch_id]);
        }

        if (request('branch')) {
            $departments = Department::where('department_branch_id', $request->branch)->get();
            return response()->json(['departments' => $departments, 'department' => $employee->department_id]);
        }

        if (request('role')) {
            $jobClasses = JobClass::where('role_id', $request->role)->get();
            return response()->json(['jobClasses' => $jobClasses, 'jobClass' => $employee->job_class_id]);
        }

        if (request('jobclass')) {
            $jobPositions = JobPosition::where('job_class_id', $request->jobclass)->get();
            return response()->json(['jobPositions' => $jobPositions, 'jobPosition' => $employee->job_position_id]);
        }

        return view('employees.show', compact(
            'holdings',
            'roles',
            'religions',
            'employmentStatuses',
            'marital_statuses',
            'employee',
            'user',
            'department',
            'profile',
            'profilePic',
            'educations',
            'experiences',
            'skills',
            'emergencyContacts',
            'vaccine'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $holdings = Company::all();
        $roles = Role::all();
        $religions = Religion::all();
        $employmentStatuses = EmploymentStatus::all();
        $marital_statuses  = MaritalStatus::all();

        $employee = Employee::where('employee_id', $id)->first();
        $user = User::where('id', $employee->user_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $vaccine = Vaccine::where('employee_id', $employee->employee_id)->first();
        $profile = $this->employeeInterface->getEmployeeNameById($user->id);
        $profilePic = $this->employeeInterface->getEmployeePicture($user->id);

        $educations = EmployeeEducation::where('employee_id', $employee->employee_id)->get();
        $experiences = EmployeeExperience::where('employee_id', $employee->employee_id)->get();
        $skills = EmployeeSkill::where('employee_id', $employee->employee_id)->get();
        $emergencyContacts = EmployeeEmergencyContact::where('employee_id', $employee->employee_id)->get();

        if (request('company')) {
            $branches = Branches::where('company_id', $request->company)->get();
            return response()->json(['branches' => $branches, 'branch' => $employee->branch_id]);
        }

        if (request('branch')) {
            $departments = Department::where('department_branch_id', $request->branch)->get();
            return response()->json(['departments' => $departments, 'department' => $employee->department_id]);
        }

        if (request('role')) {
            $jobClasses = JobClass::where('role_id', $request->role)->get();
            return response()->json(['jobClasses' => $jobClasses, 'jobClass' => $employee->job_class_id]);
        }

        if (request('jobclass')) {
            $jobPositions = JobPosition::where('job_class_id', $request->jobclass)->get();
            return response()->json(['jobPositions' => $jobPositions, 'jobPosition' => $employee->job_position_id]);
        }

        return view('employees.edit', compact(
            'holdings',
            'roles',
            'religions',
            'employmentStatuses',
            'marital_statuses',
            'employee',
            'user',
            'department',
            'profile',
            'profilePic',
            'educations',
            'experiences',
            'skills',
            'emergencyContacts',
            'vaccine'
        ));
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
        $data = $request->except(['_token', 'role_id', 'email', 'password']);
        $this->employeeInterface->updateEmployee($id, $data);

        $userId = Employee::where('employee_id', $id)->pluck('user_id');
        $userAccount = User::where('id', $userId);
        $userPassword = $userAccount->pluck('password')->first();
        $userRequest = $request->only(['email', 'password']);
        $passwordMatching = strcmp($userRequest['password'], $userPassword);

        if ($passwordMatching === 0 || Hash::check($userRequest['password'], $userPassword)) {
            $userData = $request->only(['email']);
        } else {
            $userData = $userRequest;
            $userData['password'] = bcrypt($userData['password']);
        }

        $this->employeeInterface->updateUser($userId, $userData);

        $role = $request->only('role_id');
        $userAccount->first()->syncRoles([])->assignRole($role);

        return response()->json(['message' => 'Successfully Updated Employee Data!']);
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
            $data['date_of_leaving'] = Carbon::now();

            $this->employeeInterface->deleteEmployee($id, $data);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Successfully Deleted Employee Data!']);
    }

    public function getBranches($id)
    {
        return response()->json([
            'branches' => Branches::where('company_id', $id)->get()
        ]);
    }

    public function getDepartments($id)
    {
        return response()->json([
            'departments' => Department::where('department_branch_id', $id)->get()
        ]);
    }

    public function select()
    {
        $list_all = Employee::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = ["id" => $item->employee_id, "first" => $item->first_name, "last" => $item->last_name];
        }
        return response()->json(["error" => false, "data" => $select]);
    }

    public function showEducation($id)
    {
        dd($this->employeeEducationInterface->getEmployeeEducation($id));
    }

    public function profilAllEmployeesList(Request $request)
    {
        if (request('user_id')) {
            $user = User::where('id', $request->user_id)->first();
        } else {
            $user = Auth::user('user_id');
        }

        $employee = Employee::where('user_id', $user->id)->first();
        $employees = $this->employeeInterface->getProfilEmployee()->where('employees.department_id', $employee->department_id)->get();

        return datatables()->of($employees)
            ->addIndexColumn()
            ->make(true);
    }

    public function employeeInformation(Request $request, $id)
    {
        $data = $request->except('_token', 'user_id');
        if ($request->input('user_id')) {
            $user = $request->input('user_id');
        } else {
            $user = Auth::user()->id;
        }

        $validate = Validator::make(
            $data,
            [
                'company_id' => 'required|integer',
                'branch_id' => 'required|integer',
                'department_id' => 'required|integer',
                'job_class_id' => 'required|integer',
                'job_position_id' => 'required|integer',
                'employment_status_id' => 'required|integer',
                'date_of_joining' => 'required|date',
                'role' => 'required|integer',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        ModelHasRoles::where('model_id', $user)->update(['role_id' => $data['role']]);
        $employee = Employee::where('employee_id', $id)->update([
            'company_id' => $data['company_id'],
            'branch_id' => $data['branch_id'],
            'department_id' => $data['department_id'],
            'job_class_id' => $data['job_class_id'],
            'job_position_id' => $data['job_position_id'],
            'employment_status_id' => $data['employment_status_id'],
            'date_of_joining' => $data['date_of_joining'],
        ]);

        if ($employee) {
            return response()->json(["error" => false, "message" => "Successfully Updated Employee Information Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }

    public function showAdditional()
    {
        $profileID = Auth::user()->id;
        $profile = $this->employeeInterface->getEmployeeNameById($profileID);

        $data = [
            'nik' => $profile->nik,
            'passport' => $profile->passport,
            'place_of_birth' => $profile->place_of_birth,
            'date_of_birth' => $profile->date_of_birth,
            'blood_type' => $profile->blood_type,
            'religion' => $profile->religion,
            'nationality' => $profile->nationality,
            'marital_status' => $profile->marital_status,
            'address' => $profile->address,
            'marital' => $profile->marital_status_id,
            'religions' => $profile->religion_id
        ];

        return $data;
    }

    public function updateAdditional(Request $request, $id)
    {
        $emp = $request->except('_token');
        $employee = Employee::where('employee_id', $id)->first();
        $emp['employee_id'] = $employee->employee_id;

        $validate = Validator::make(
            $emp,
            [
                'nik' => 'required|size:16',
                'passport' => 'required|size:7',
                'place_of_birth' => 'required|max:191',
                'date_of_birth' => 'required|date',
                'blood_type' => 'required|max:2',
                'nationality' => 'required',
                'religion_id' => 'required|integer',
                'marital_status_id' => 'required|integer',
                'address' => 'required|string',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $religion = Religion::where('religion_id', $request->input('religion_id'))->first()->religion;
        $marital = MaritalStatus::where('marital_status_id', $request->input('marital_status_id'))->first()->marital_status;
        if ($request->input('user_id')) {
            $data = Employee::where('employee_id', $id)->update([
                "nik" => $request->input('nik'),
                "passport" => $request->input('passport'),
                "place_of_birth" => $request->input('place_of_birth'),
                "date_of_birth" => $request->input('date_of_birth'),
                "blood_type" => $request->input('blood_type'),
                "religion_id" => $request->input('religion_id'),
                "nationality" => $request->input('nationality'),
                "marital_status_id" => $request->input('marital_status_id'),
                "address" => $request->input('address'),
            ]);
        } else {
            $data = TempEmployee::create([
                "employee_id" => $employee->employee_id,
                "data" => json_encode([
                    "nik" => $request->input('nik'),
                    "passport" => $request->input('passport'),
                    "place_of_birth" => $request->input('place_of_birth'),
                    "date_of_birth" => $request->input('date_of_birth'),
                    "blood_type" => $request->input('blood_type'),
                    "religion_id" => $request->input('religion_id'),
                    "marital_status_id" => $request->input('marital_status_id'),
                    "address" => $request->input('address'),
                    "religion_name" => $religion,
                    "relationship" => $marital,
                    "nationality" =>  $request->input('nationality'),
                ]),
                "employee_information" => "Employee Profile",
                "category_information" => "Additional Information"
            ]);
        }

        if ($data) {
            return response()->json(["error" => false, "message" => "Updated Data has been sent, Please wait for it to be approved"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }

    public function editProfile($id)
    {
        $user = Auth::user('user_id');
        $data = $this->employeeInterface->getProfilEmployee()->where('user_id', $user->id)->first();

        $data = [
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'phone' => $data->phone,
            'email' => $data->email,
            'branch_name' => $data->branch_name,
            'department_name' => $data->department_name,
            'job_position' => $data->job_position,
            'instagram_link' => $data->instagram_link,
            'linkedin_link' => $data->linkedin_link,
            'facebook_link' => $data->facebook_link
        ];

        return $data;
    }

    public function updateProfile(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        $employee = Employee::where('employee_id', $id)->first();
        $user = User::where('id', $employee->user_id)->first();

        $validate = Validator::make(
            $data,
            [
                'first_name' => 'required|max:191',
                'last_name' => 'required|max:191',
                'email' => ['required', 'email:dns', Rule::unique('users')->ignore($user->id, 'id')],
                'phone' => ['required', 'max:13', Rule::unique('employees')->ignore($id, 'employee_id')],
                'image' => 'image|file|max:1024',
                'facebook_link' => 'max:191',
                'instagram_link' => 'max:191',
                'linkedin_link' => 'max:191',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $data['image'] = $user->image;
        if ($request->file('image')) {
            if ($user->image) {
                Storage::delete($user->image);
            }
            $data['image'] = $request->file('image')->store('profile-images');
        }

        User::where('id', $user->id)->update([
            'email' => $data['email'],
        ]);

        $data = Employee::where('employee_id', $id)->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'facebook_link' => $data['facebook_link'],
            'instagram_link' => $data['instagram_link'],
            'linkedin_link' => $data['linkedin_link'],
            'image' => $data['image'],
        ]);

        if ($data) {
            return response()->json(["error" => false, "message" => "Successfully Updated Basic Information Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }

    public function storeVaccine(Request $request)
    {
        if ($request->input('user_id')) {
            $employee = Employee::where('user_id', $request->input('user_id'))->first();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
        }
        $data = $request->file();
        $vaccine = Vaccine::where('employee_id', $employee->employee_id)->first();

        $rules = [];
        if ($vaccine) {
            if ($request->file('vaccine_1')) {
                if ($request->file('vaccine_1')->getClientOriginalName() !== $vaccine->vaccine_path) {
                    $rules['vaccine_1'] = 'file|max:10240|mimes:jpeg,png,jpg';
                }
            }

            if ($request->file('vaccine_2')) {
                if ($request->file('vaccine_2')->getClientOriginalName() !== $vaccine->vaccine_path) {
                    $rules['vaccine_2'] = 'file|max:10240|mimes:jpeg,png,jpg';
                }
            }

            if ($request->file('vaccine_3')) {
                if ($request->file('vaccine_3')->getClientOriginalName() !== $vaccine->vaccine_path) {
                    $rules['vaccine_3'] = 'file|max:10240|mimes:jpeg,png,jpg';
                }
            }

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json([
                    'error' => $validate->errors()->toArray()
                ]);
            }

            $vaccine1 = $vaccine->vaccine_1;
            $vaccine2 = $vaccine->vaccine_2;
            $vaccine3 = $vaccine->vaccine_3;

            if ($request->file('vaccine_1')) {
                if ($vaccine->vaccine_1) {
                    Storage::delete($vaccine->vaccine_1);
                }
                $vaccine1 = $request->file('vaccine_1')->store('vaccines');
            }

            if ($request->file('vaccine_2')) {
                if ($vaccine->vaccine_2) {
                    Storage::delete($vaccine->vaccine_2);
                }
                $vaccine2 = $request->file('vaccine_2')->store('vaccines');
            }

            if ($request->file('vaccine_3')) {
                if ($vaccine->vaccine_3) {
                    Storage::delete($vaccine->vaccine_3);
                }
                $vaccine3 = $request->file('vaccine_3')->store('vaccines');
            }

            Vaccine::where('id', $vaccine->id)->update([
                'employee_id' => $employee->employee_id,
                'vaccine_1' => $vaccine1,
                'vaccine_2' => $vaccine2,
                'vaccine_3' => $vaccine3,
            ]);

            return response()->json(["error" => false, "message" => "Successfully Updated Vaccine Certificate Data!"]);
        } else {
            $validate = Validator::make(
                $data,
                [
                    'vaccine_1' => 'file|max:10240|mimes:jpeg,png,jpg',
                    'vaccine_2' => 'file|max:10240|mimes:jpeg,png,jpg',
                    'vaccine_3' => 'file|max:10240|mimes:jpeg,png,jpg',
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'error' => $validate->errors()->toArray()
                ]);
            }

            $vaccine1 = null;
            $vaccine2 = null;
            $vaccine3 = null;

            if ($request->file('vaccine_1')) {
                $vaccine1 = $request->file('vaccine_1')->store('vaccines');
            }

            if ($request->file('vaccine_2')) {
                $vaccine2 = $request->file('vaccine_2')->store('vaccines');
            }

            if ($request->file('vaccine_3')) {
                $vaccine3 = $request->file('vaccine_3')->store('vaccines');
            }

            Vaccine::create([
                'employee_id' => $employee->employee_id,
                'vaccine_1' => $vaccine1,
                'vaccine_2' => $vaccine2,
                'vaccine_3' => $vaccine3,
            ]);

            return response()->json(["error" => false, "message" => "Successfully Updated Vaccine Certificate Data!"]);
        }
    }

    public function updateImageProfile(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        $employee = Employee::where('employee_id', $id)->first();
        $user = User::where('id', $employee->user_id)->first();

        $data = Employee::where('employee_id', $id)->update([
            'image' => null,
        ]);

        if ($data) {
            return response()->json(["error" => false, "message" => "Successfully Updated Basic Information Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }
}
