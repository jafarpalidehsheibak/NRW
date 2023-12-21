<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupervisorCollection;
use App\Http\Resources\SupervisorResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
//        $supervisors = Supervisor::where('role_id', '=', 3)->paginate(10);
        $supervisors =DB::table('users')
            ->join('profiles','users.id','=','profiles.user_id')
            ->join('roles','roles.id','=','users.role_id')
            ->join('provinces','provinces.id','=','profiles.province_id')
            ->join('experts','experts.id','=','profiles.expert_id')
            ->where('users.role_id','=',3)->paginate(10);
        return response()->json(
            new SupervisorCollection($supervisors)
        , 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|digits:11|numeric|regex:/(09)[0-9]{9}/|unique:users',
            'password' => 'required|string|min:8|max:255',
            'phone_number' => 'nullable|digits:11|regex:/(0)[0-9]{10}/|numeric|',
            'expert' => 'nullable|numeric|exists:experts,id',
            'province' => 'nullable|numeric|exists:provinces,id',
        ]);
        try {
            DB::beginTransaction();
            $password = Hash::make($request->input('password'));
            $res = Supervisor::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $password,
                'role_id' => 3
            ]);
            $res2 = Profile::create([
                'phone_number' => $request->input('phone_number'),
                'user_id' => $res->id,
                'expert_id' => $request->input('expert'),
                'province_id' => $request->input('province'),
            ]);
            DB::commit();
            if ($res && $res2) {
                return response()->json([
                    'data' => [
                        'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
                    ],
                ], 201);
            }
        }
        catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'data' => [
                    'message' => 'خطا در ثبت اطلاعات'
                ],
            ], 400);
        }
    }

    public function show($id)
    {
        $supervisor =DB::table('users')
            ->join('profiles','users.id','=','profiles.user_id')
            ->join('roles','roles.id','=','users.role_id')
            ->join('provinces','provinces.id','=','profiles.province_id')
            ->join('experts','experts.id','=','profiles.expert_id')
            ->where('users.role_id','=',3)
            ->where('users.id','=',$id)
            ->paginate(10);
        if ($supervisor->count() == 0) {
            return response()->json([
                'message' => 'رکوردی مورد نظر یافت نشد'
            ]);
        } else {
            return response()->json(
                new SupervisorCollection($supervisor)
                , 200);
        }
    }

    public function update(Request $request, $id)
    {
        $supervisor = Supervisor::query()->where('id', '=', $id)
            ->where('role_id', '=', 3)
            ->get();
        if ($supervisor->count() == 0) {
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
                $supervisor->first()->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => $password,
                ]);
            } else {
                $supervisor->first()->update([
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
        $supervisor = Supervisor::query()
            ->where('id', '=', $id)
            ->where('role_id', '=', 3)
            ->get();
//        dd($supervisor);
        if ($supervisor->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $supervisor->first()->delete();
            return response()->json([
                'data' => 'رکورد مورد نظر با موفقیت حذف شد'
            ], 200);
        }
    }
}
