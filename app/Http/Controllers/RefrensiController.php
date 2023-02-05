<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Angkatan;
use App\Models\Provinsi;
use App\Models\KotaKab;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class RefrensiController extends Controller
{
  
    public function select_provinsi()
    {
        $list_all = Provinsi::all();

        $select=[["id"=>"","text"=>"Tidak Ada Provinsi"]];

        foreach ($list_all as $item) {
            $select[]=["id"=>$item->id_provinsi,"text"=>$item->nama_provinsi];
        }
        return response()->json(["error"=>false,"data"=>$select]);

    }

    public function select_kotakab()
    {
        $list_all = KotaKab::all();

        $select=[["id"=>"","text"=>""]];

        foreach ($list_all as $item) {
            $select[]=["id"=>$item->id_kotakab,"text"=>$item->nama_kotakab];
        }
        return response()->json(["error"=>false,"data"=>$select]);

    }

  }
