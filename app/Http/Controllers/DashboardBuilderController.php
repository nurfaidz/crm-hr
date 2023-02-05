<?php

namespace App\Http\Controllers;

use App\Models\UserHasDashboard;
use Illuminate\Http\Request;

class DashboardBuilderController extends Controller
{
    public function update($id, Request $request)
    {
        $widget = UserHasDashboard::where('user_id', $request->user()->id)->where('dashboard_id', $id)->first();
        if ($widget->is_active == 1) {
            $widget->update(['is_active' => 0]);
            return response()->json(["error" => false, "message" => "Successfully Updated Widget!"]);
        } else {
            $widget->update(['is_active' => 1]);
            return response()->json(["error" => false, "message" => "Successfully Updated Widget!"]);
        }
    }
}
