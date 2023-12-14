<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupervisorResource;
use App\Http\Resources\UserResource;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function index()
    {
        $supervisors = Supervisor::where('role','=',11)->get();
        return response()->json([
            'data'=>SupervisorResource::collection($supervisors)
            ],200);
    }
}
