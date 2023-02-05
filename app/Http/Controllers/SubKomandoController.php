<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Angkatan;
use App\Models\SubKomando;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class SubKomandoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        return view('master.subkomando.index');
    }

    public function get_list(Request $request){
        $angkatan = Angkatan::where("level","sub")->with('provinsi','parent')->latest()->get();
        return Datatables::of($angkatan)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a onclick=edit_data($(this)) data-id="'.$row->id_angkatan.'"  class="edit btn btn-success btn-sm">Edit</a> 
                    <a data-id="'.$row->id_angkatan.'" onclick=delete_data($(this)) class="btn btn-sm btn-danger" >Delete</a>
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
        $requestData["level"]="sub";
        Angkatan::create($requestData);
        return response()->json(["error"=>false,"message"=>"Successfuly Added Sub Komando!"]);
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
        $role = Angkatan::find($id);

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
        
        $role = Angkatan::findOrFail($id);
        $role->update($requestData);

        if($role){

            return response()->json(["error"=>false,"message"=>"Successfully Update Sub Komando"]);
        
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

            Angkatan::destroy($id);

        } catch (Exception $e) {
     
            return response()->json(["error"=>true,"message"=>$e->getMessage()]);

        }

        return response()->json(["error"=>false,"message"=>"Successfuly Deleted Sub Komando!"]);

    }

    public function select($parent=null)
    {
        $list_all = ($parent) ? Angkatan::where("level","sub")->where("parent",$parent)->get() : Angkatan::where("level","sub")->get();

        $select=[];

        foreach ($list_all as $item) {
            $select[]=["id"=>$item->id_angkatan,"text"=>$item->nama_angkatan];
        }
        return response()->json(["error"=>false,"data"=>$select]);

    }

  }
