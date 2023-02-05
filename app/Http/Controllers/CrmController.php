<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CustomerSeeder;
use Event;

class CrmController extends Controller
{
    public function index()
    {
    	$customer = Customer::all();
		// $pegawai = DB::table('customer')->paginate(10);
    	return view('customer', ['customer' => $customer]);
    } 
	
	
}
