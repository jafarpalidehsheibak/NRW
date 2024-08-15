<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityeController extends Controller
{
    public function index($id)
    {
        $cities = DB::table('cities')
            ->where('cities.province_id','=',$id)
            ->select('cities.id','cities.city_name')
            ->get();
        return response()->json([
            'data'=>$cities
        ],200);
        //comment
    }
}
