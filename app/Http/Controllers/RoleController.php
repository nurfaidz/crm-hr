<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\RoleHas;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role_or_permission:Super Admin|role_permission.list', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Super Admin|role_permission.create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Super Admin|role_permission.edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Super Admin|role_permission.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::get();
        $role = Role::latest()->get();

        return view('roles.role.index', compact('role', 'permissions'));
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
        $data = $request->only('name', 'permission_id');
        $validatedData = Validator::make($data, [
            'name' => 'required|max:191|unique:roles'
        ]);
        if ($validatedData->fails()) {
            $messages = $validatedData->getMessageBag()->get('name');
            return response()->json(['error' => true, 'message' => $messages[0]], 400);
        }

        $permission_id = $request->permission_id;

        $result = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        foreach ($permission_id as $key => $value) {
            $prevent = RoleHas::where('permission_id', $value)->where('role_id', $result->id);

            if ($prevent->get()->isEmpty()) {
                $rhp = new RoleHas();
                $rhp->permission_id = $value;
                $rhp->role_id = $result->id;
                $rhp->save();
            } else {
                $already = $prevent->first();
                $already->save();
            }
        }

        Artisan::call('cache:clear');

        return response()->json(["error" => false, "message" => "Successfully Added Role!"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $myPermissions = RoleHas::where('role_id', $role->id)->get();

        return response()->json([
            'permissions' => $myPermissions,
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role_name = $request->name;
        $permission_id = $request->permission_id;
        $data = $request->only('name', 'permission_id');

        if ($request->name != $role->name) {
            $validatedData = Validator::make($data, [
                'name' => 'required|max:191|unique:roles'
            ]);

            if ($validatedData->fails()) {
                $messages = $validatedData->getMessageBag()->get('name');
                return response()->json(['error' => true, 'message' => $messages[0]], 400);
            }
        }

        if ($permission_id == null) {
            RoleHas::where('role_id', $role->id)->delete();
            $role = Role::findOrFail($role->id);
            $role->update(['name' => $role_name]);
            return response()->json(["error" => false, "message" => "Successfully Updated Role!"]);
        } else {
            RoleHas::where('role_id', $role->id)->delete();
            foreach ($permission_id as $key => $value) {
                $prevent = RoleHas::where('permission_id', $value)->where('role_id', $role->id);

                if ($prevent->get()->isEmpty()) {
                    $rhp = new RoleHas();
                    $rhp->permission_id = $value;
                    $rhp->role_id = $role->id;
                    $rhp->save();
                } else {
                    $already = $prevent->first();
                    $already->save();
                }
            }

            $role = Role::findOrFail($role->id);
            $role->update(['name' => $role_name]);

            Artisan::call('cache:clear');

            return response()->json(["error" => false, "message" => "Successfully Updated Role!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        Role::destroy($role->id);

        return response()->json(["error" => false, "message" => "Successfully Deleted Role!"]);
    }

    public function get_role_ajax(Request $request)
    {
        $role = Role::latest()->get();
        return Datatables::of($role)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<button type="button" title="Edit" data-toggle="modal" data-target="#modal-form" data-id="' . $row->id . '" class="edit btn btn-icon btn-success mx-auto" id="edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/roles/delete/' . $row->id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn btn-icon btn-danger mx-auto " onclick="sweetConfirm(' . $row->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function select()
    {
        $list_all = Role::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = ["id" => $item->id, "text" => $item->name];
        }
        return response()->json(["error" => false, "data" => $select]);
    }
}
