<?php

namespace App\Http\Controllers;

use App\Models\ManageHoliday;
use App\Models\NationalHoliday;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManageHolidayController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage_holiday.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_holiday.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_holiday.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_holiday.delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $holidays = ManageHoliday::all();

        if ($request->ajax()) {
            return datatables()->of($holidays)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    return '<button type="submit" data-toggle="modal" data-target="#modal-form" data-id="' . $data->holiday_id . '" class="edit btn p-0 mx-1" id="edit"><img src="./img/icons/edit.svg" alt="Edit"></button> <form id="form_delete_data" style="display:inline" class="" action="/companies/delete/' . $data->holiday_id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn p-0 mx-1" onclick="sweetConfirm(' . $data->holiday_id . ')"><img src="./img/icons/trash.svg" alt="Delete"></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('manage-holidays.index');
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

        $validate = Validator::make(
            $data,
            [
                'holiday_occasion' => 'required|unique:manage_holidays',
                'description' => 'required',
                'status' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        ManageHoliday::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added Holiday Data!"
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
        $where = array('holiday_id' => $id);
        $post  = ManageHoliday::where($where)->first();

        return response()->json($post);
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
        $data = $request->except(['_token']);

        $validate = Validator::make(
            $data,
            [
                'holiday_occasion' => [
                    'required',
                    Rule::unique('manage_holidays')->ignore($id, 'holiday_id')
                ],
                'description' => 'required',
                'status' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $holiday = ManageHoliday::where('holiday_id', $id);
        $holiday->update($data);

        if ($holiday) {
            return response()->json(["error" => false, "message" => "Successfully Updated Holiday Data!"]);
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
            ManageHoliday::where('holiday_id', $id)->delete();
            NationalHoliday::where('holiday_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfuly Deleted Holiday Data!"]);
    }
}