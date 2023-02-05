<?php

namespace App\Http\Controllers;

use App\Models\Bank;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::all();
        if ($request->ajax()) {
            return datatables()->of($banks)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<a data-id="' . $data->bank_id . '" class="edit btn btn-info btn-sm" onclick=edit_data($(this))><i class="far fa-edit"></i> Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a data-id="' . $data->bank_id . '" onclick=delete_data($(this)) class="btn btn-sm btn-danger">Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('bank/index',);
    }

    public function get_list()
    {
        try {
            $bank = Bank::all();
            return $bank;
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'No Data', 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'bank_name' => 'required|unique:banks',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }
        Bank::create($data);
        return response()->json([
            "error" => false,
            "message" => "Successfuly Added Bank Data!"
        ]);
    }

    public function edit($id)
    {
        $where = array('bank_id' => $id);
        $post  = Bank::where($where)->first();

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'bank_name' => [
                'required',
                Rule::unique('banks')->ignore($id, 'bank_id'),
            ],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }
        $data = request()->except(['_token']);
        $bank = Bank::where('bank_id', $id);
        $bank->update($data);
    }

    public function destroy($id)
    {
        try {
            Bank::where('bank_id', $id)->delete();
        } catch (Exception $e) {

            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Successfuly Deleted Bank Data!"]);
    }

    public function select()
    {
        $list_all = Bank::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = ["id" => $item->bank_id, "text" => $item->bank_name];
        }
        return response()->json(["error" => false, "data" => $select]);
    }
}
