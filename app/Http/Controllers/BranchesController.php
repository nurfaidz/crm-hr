<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BranchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:branch_company.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:branch_company.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch_company.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:branch_company.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$branches = Branches::All();
        //return dd($branches);
        return view('branches.index');
    }

    public function get_list()
    {

        //$branches = Branches::select(['branch_id', 'branch_name', 'company_id']);
        //$branches = Branches::where("branches")->latest()->with('company')->get();
        $branches = Branches::leftjoin('companies', 'branches.company_id', '=', 'companies.company_id')->get();
        //$branches = Branches::with('company')->get(['branch_id', 'branch_name', 'company_id']);

        return DataTables::of($branches)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn  = '<a title="Edit" onclick=edit_data($(this)) data-id="' . $row->branch_id . '"  class="edit btn btn-icon btn-success mx-auto "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
                $actionBtn .= ' ';
                $actionBtn .= '<a title="Delete" data-id="' . $row->branch_id . '" onclick=delete_data($(this)) class="btn btn-icon btn-danger mx-auto"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        //return dd($branches);
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
        $requestData = $request->all();
        $validate = Validator::make(
            $requestData,
            [
                'branch_name' => 'required|unique:branches',
                'company_id' => 'required',

            ],
            [
                'company_id.required' => 'The company field is required.',
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }
            Branches::create($requestData);
            return response()->json([
                "error" => false,
                "message" => "Successfuly Added Branch Data!"
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
        $branches = Branches::find($id);

        if ($branches) {
            return response()->json(["error" => false, "data" => $branches]);
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
        $requestData = $request->all();
        $validate = Validator::make(
            $requestData,
            [
                'branch_name' => 'required|unique:branches',
                'company_id' => 'required',

            ],
            [
                'branch_name.unique' => 'Branch name is already taken.',
                'branch_name.required' => 'The branch field is required.',
                'company_id.required' => 'The company field is required.',
            ]
        );
        
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $branches = Branches::findOrFail($id);
        $branches->update($requestData);

        //return dd($request)->json();

        if ($branches) {
            return response()->json(["error" => false, "message" => "Successfully Update Branch"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Not Found"]);
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
        //$branches = Branches::find($id);
        //$branches->delete();
        try {
            Branches::destroy($id);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfuly Deleted Branch Data!"]);
    }

    public function select()
    {
        $list_all = Branches::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = ["id" => $item->branch_id, "text" => $item->branch_name];
        }
        return response()->json(["error" => false, "data" => $select]);
    }
}