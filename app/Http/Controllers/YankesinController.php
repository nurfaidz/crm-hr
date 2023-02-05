<?php

namespace App\Http\Controllers;

use App\Models\BOR;
use App\Models\Event;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class YankesinController extends Controller
{
    public function index()
    {
    	// $event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('yankesin/monitoring-bor');
    } 

    public function monitoring_bor()
    {
    	// $event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('yankesin/monitoring-bor');
    } 


    /* Data BOR */
    
    public function input_bor()
    {
    	return view('yankesin/input/bor');
    } 

    public function input_bor_store(Request $request)
    {
        
        $requestData = $request->all(); 
        BOR::create($requestData);
        return response()->json(["error"=>false,"message"=>"Successfuly Added BOR Data!"]);

    }

    public function input_bor_edit($id)
    {
        $role = BOR::find($id);

        if($role){

            return response()->json(["error"=>false,"data"=>$role]);
        
        }else{

            return response()->json(["error"=>true,"message"=>"Data Not Found"]);

        }
    }

    public function input_bor_update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $role = BOR::findOrFail($id);
        $role->update($requestData);

        if($role){

            return response()->json(["error"=>false,"message"=>"Successfully Update BOR"]);
        
        }else{

            return response()->json(["error"=>true,"message"=>"Data Not Found"]);

        }

    }

    public function input_bor_destroy($id)
    {
        try {

            BOR::destroy($id);

        } catch (Exception $e) {
     
            return response()->json(["error"=>true,"message"=>$e->getMessage()]);

        }

        return response()->json(["error"=>false,"message"=>"Successfuly Deleted BOR Data!"]);

    }

    public function get_list_bor(Request $request){
        $angkatan = BOR::latest()->get();
        return Datatables::of($angkatan)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a onclick=edit_data($(this)) data-id="'.$row->id_bor.'"  class="edit btn btn-success btn-sm">Edit</a> 
                    <a data-id="'.$row->id_bor.'" onclick=delete_data($(this)) class="btn btn-sm btn-danger" >Delete</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function monitoring_pasien_covid()
    {
    	// $event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('yankesin/monitoring-covid');
    } 

    public function monitoring_nakes()
    {
    	// $event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('yankesin/monitoring-nakes');
    } 
}
