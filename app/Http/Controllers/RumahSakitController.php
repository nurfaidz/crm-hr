<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Angkatan;
use App\Models\RumahSakit;
use App\Models\SubKomando;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class RumahSakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        return view('master.rumahsakit.index');
    }

    public function get_list(Request $request){
        $angkatan = RumahSakit::with('angkatan','kotakab')->latest()->get();
        return Datatables::of($angkatan)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a onclick=edit_data($(this)) data-id="'.$row->id_rs.'"  class="edit btn btn-success btn-sm">Edit</a> 
                    <a data-id="'.$row->id_rs.'" onclick=delete_data($(this)) class="btn btn-sm btn-danger" >Delete</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('roles.role.create');
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
        RumahSakit::create($requestData);
        return response()->json(["error"=>false,"message"=>"Successfuly Added Rumah Sakit!"]);
        // return redirect()->route('roles.index')->with('flash_message', 'Role added!');
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
        // $role = Role::findOrFail($id);

        // return view('roles.role.show', compact('role'));
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
        $role = RumahSakit::find($id);

        if($role){

            return response()->json(["error"=>false,"data"=>$role]);
        
        }else{

            return response()->json(["error"=>true,"message"=>"Data Not Found"]);

        }


        // return view('roles.role.edit', compact('role', 'permissions'));
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
        
        $role = RumahSakit::findOrFail($id);
        $role->update($requestData);

        if($role){

            return response()->json(["error"=>false,"message"=>"Successfully Update Rumah Sakit"]);
        
        }else{

            return response()->json(["error"=>true,"message"=>"Data Not Found"]);

        }


        // return redirect()->route('roles.index')->with('flash_message', 'Role updated!');
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
        try {

            RumahSakit::destroy($id);

        } catch (Exception $e) {
     
            return response()->json(["error"=>true,"message"=>$e->getMessage()]);

        }

        return response()->json(["error"=>false,"message"=>"Successfuly Deleted Rumah Sakit!"]);

    }

    public function select()
    {
        $list_all = RumahSakit::all();

        $select=[];

        foreach ($list_all as $item) {
            $select[]=["id"=>$item->id_rs,"text"=>$item->nama_rs];
        }
        return response()->json(["error"=>false,"data"=>$select]);

    }

  }
