<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Exception;
use App\Models\LateMealTransport;
use DataTables;
use Illuminate\Support\Facades\Validator;


class LateMealController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master_late.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:master_late.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:master_late.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:master_late.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return view('kehadiran.keterlambatan.index', [
        //     'late_mealtrans' => LateMealTransport::where('id', auth()->user()->id)->get()
        // ]);


        $list_late = LateMealTransport::all();
        // return Datatables::of($list_late)
        if ($request->ajax()){
            return datatables()->of($list_late)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    $button = '<a data-id="' . $data->id . '" class="edit btn btn-icon btn-success mx-auto" onclick=edit_data($(this))><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a data-id="' . $data->id . '" class="btn btn-icon btn-danger mx-auto" onclick=delete_data($(this))><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('late.index');
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
        $validate = Validator::make($data, [
            'start_minutes' => 'required|numeric|min:0|max:59',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        LateMealTransport::create($data);
        return response()->json([
            "error" => false,
            "message" => "Successfully Added Late Data!"
        ]);

        // $request->validate(LateMealTransport::$rules);

        // $valid = $request->all();
        // LateMealTransport::create($valid);

        // return response()->json([
        //     "error" => false,
        //     "message" => "Successfully Added!"
        // ]);
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
        $r = LateMealTransport::find($id);

        if ($r) {
            return response()->json(["error" => false, "data" => $r]);
        } else {
            return response()->json(["error" => true, "message" => "Data Not Found"]);
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

        $data = $request->all();
        $validate = Validator::make($data, [
            'start_minutes' => 'required|numeric|min:0|max:59',
            'percentage' => 'required|numeric|min:0|max:100'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $r = LateMealTransport::findOrFail($id);
        $r->update($data);

        if($r) {
            return response()->json(['error' => false, 'message' => 'Successfully Update']);
        } else {
            return response()->json(['error' => true, 'message' => 'Data Not Found']);
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
            LateMealTransport::where('id', $id)->delete();
        } catch (Exception $e) {

            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }

        return response()->json(['error' => false, 'message' => 'Successfully Deleted Data!']);
        
    }
}