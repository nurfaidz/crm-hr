<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ReferensiController extends Controller
{
    public function index()
    {
    	// $event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('yankesin/monitoring');
    } 
}
