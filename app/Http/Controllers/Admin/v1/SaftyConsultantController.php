<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SafetyConsultantCollection;
use App\Http\Resources\SafetyConsultantResource;
use App\Models\SafetyConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SaftyConsultantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $safety_consultant = SafetyConsultant::where('role_id', '=', 4)->paginate(10);
        return response()->json(
            new SafetyConsultantCollection($safety_consultant)
        , 200);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|digits:11|numeric|regex:/(0)[0-9]{10}/|unique:users',
            'password' => 'required|string|min:8|max:255',
        ]);
        $password = Hash::make($request->input('password'));
        $res = SafetyConsultant::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $password,
            'role_id' => 4
        ]);
        if ($res) {
            return response()->json([
                'data' => [
                    'name' => $res->name,
                    'username' => $res->email,
                ],
                'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
            ], 201);
        }
    }
    public function show($id)
    {
        $safety_consultant = SafetyConsultant::query()
            ->where('id', '=', $id)
            ->where('role_id', '=', 4)
            ->get();
        if ($safety_consultant->count() == 0) {
            return response()->json([
                'message' => 'رکوردی مورد نظر یافت نشد'
            ]);
        } else {
            return response()->json([
                'data' => [
                    'name' => $safety_consultant[0]['name'],
                    'email' => $safety_consultant[0]['email'],
                ]
            ], 200);
        }
    }
    public function update(Request $request, $id)
    {
        $safety_consultant = SafetyConsultant::query()->where('id', '=', $id)
            ->where('role_id', '=', 4)
            ->get();
        if ($safety_consultant->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
           $this->validate($request, [
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|digits:11|numeric|regex:/(0)[0-9]{10}/|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|max:255',
            ]);
            if ($request->has('password')) {
                $password = Hash::make($request->input('password'));
                $safety_consultant->first()->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => $password,
                ]);
            } else {
                $safety_consultant->first()->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                ]);
            }
            return response()->json([
                'data' => 'رکورد مورد نظر با موفقیت ویرایش شد'
            ], 201);
        }
    }
    public function destroy($id)
    {
        $safety_consultant = SafetyConsultant::query()
            ->where('id', '=', $id)
            ->where('role_id', '=', 4)
            ->get();
        if ($safety_consultant->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $safety_consultant->first()->delete();
            return response()->json([
                'data' => 'رکورد مورد نظر با موفقیت حذف شد'
            ], 200);
        }
    }



}
