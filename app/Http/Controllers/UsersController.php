<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\ModelHasRoles;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHas;
use DB;
use Hash;
// use Illuminate\Database\Console\DbCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user_list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user_delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $role = json_encode(Auth::user()->hasPermissionTo('user_list'));
        if ($role) {

            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $user = User::latest()->paginate($perPage);
            } else {
                $user = User::latest()->paginate($perPage);
            }
        }
        return view('users.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        // return $roles;
        return view('users.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $requestData = $request->all();
        $requestData['password'] = Hash::make($requestData['password']);

        $user = User::create($requestData);
        // $user = User::create($input);
        // $inputModelHas['role_id'] = $request->role_id;
        // $inputModelHas['model_type'] = "App\Models\User";
        // $inputModelHas['model_id'] = $user->id;
        // $modelHas = ModelHasRoles::create($inputModelHas);

        $mhr = new ModelHasRoles();
        $mhr->role_id = $request->role_id;
        $mhr->model_type = "App\Models\User";
        $mhr->model_id = $user->id;
        $mhr->save();
        // $user->assignRole($request->input('role_id'));

        return redirect()->route('users.index')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.user.edit', compact('user', 'roles', 'userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $requestData = $request->all();
        if (!empty($requestData['password'])) {
            $requestData['password'] = Hash::make($requestData['password']);
        } else {
            $requestData = Arr::except($requestData, array('password'));
        }

        $user = User::findOrFail($id);
        $user->update($requestData);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect()->route('users.index')->with('flash_message', 'User deleted!');
    }
}
