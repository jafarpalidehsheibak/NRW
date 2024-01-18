<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = DB::table('provinces')
            ->select('provinces.id','provinces.province_name')
            ->get();
        return response()->json([
            'data'=>$provinces
        ],200);
    }
}
