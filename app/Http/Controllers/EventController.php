<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index2()
    {
    	$event = Event::all();
		// $pegawai = DB::table('event')->paginate(10);
    	return view('event', ['event' => $event]);
    } 
}
