<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpertController extends Controller
{
    public function index()
    {
        $experts = DB::table('experts')
            ->select('experts.id','experts.name_expert')
            ->get();
        return response()->json([
            'data'=>$experts
        ],200);
    }

}
