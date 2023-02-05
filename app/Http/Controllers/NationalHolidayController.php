<?php

namespace App\Http\Controllers;

use App\Models\ManageHoliday;
use App\Models\NationalHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NationalHolidayController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:national_holiday.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:national_holiday.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:national_holiday.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:national_holiday.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nationalHolidays = NationalHoliday::join('manage_holidays', 'national_holidays.holiday_id', '=', 'manage_holidays.holiday_id');
        $holidays = ManageHoliday::where('status', '=', 1)->get();

        if ($request->ajax()) {
            return datatables()->of($nationalHolidays)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<button type="submit" data-toggle="modal" data-target="#modal-form" data-id="' . $data->holiday_id . '" class="edit btn p-0" id="edit"><img src="./img/icons/edit.svg" alt="Edit"></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('national-holidays.index', compact('holidays'));
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
                'holiday_id' => 'required|unique:national_holidays,holiday_id',
                'date_range' => 'required'
            ],
            [
                'holiday_id.required' => 'The holiday occasion field is required.',
                'holiday_id.unique' => 'The holiday occasion has been taken.'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        NationalHoliday::create($data);

        return response()->json([
            "error" => false,
            "message" => "Successfully Added National Holiday Data!"
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
        $post  = NationalHoliday::where($where)->first();

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
                'holiday_id' => [
                    'required',
                    Rule::unique('national_holidays')->ignore($id, 'holiday_id')
                ],
                'date_range' => 'required'
            ],
            [
                'holiday_id.required' => 'The holiday occasion field is required.'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $data = $request->except(['_token', 'date_range']);

        $nationalHoliday = NationalHoliday::where('national_holiday_id', $id);
        $nationalHoliday->update($data);

        if ($nationalHoliday) {
            return response()->json(["error" => false, "message" => "Successfully Updated National Holiday Data!"]);
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
        //
    }
}
