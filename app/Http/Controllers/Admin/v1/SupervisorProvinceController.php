<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupervisorProvinceCollection;
use App\Models\Profile;
use App\Models\SupervisorProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SupervisorProvinceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
//        $supervisors = Supervisor::where('role_id', '=', 3)->paginate(10);
        $supervisors = DB::table('users')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('experts', 'experts.id', '=', 'profiles.expert_id')
            ->leftJoin('provinces', 'provinces.id', '=', 'profiles.province_id')
            ->select('users.*', 'profiles.phone_number', 'roles.role_name', 'experts.name_expert'
                ,'experts.id as expertId', 'provinces.province_name','provinces.id as provinceId')
            ->where('users.role_id', '=', 6)->paginate(10);
        return response()->json(
            new SupervisorProvinceCollection($supervisors)
            , 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|digits:11|numeric|regex:/(09)[0-9]{9}/|unique:users',
            'password' => 'required|string|min:8|max:255',
            'phone_number' => 'nullable|digits:11|regex:/(0)[0-9]{10}/|numeric|',
            'province' => 'required|numeric|exists:provinces,id',
            'expert' => 'nullable|numeric|exists:experts,id',
        ]);
        try {
            DB::beginTransaction();
            $password = Hash::make($request->input('password'));
            $res = SupervisorProvince::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $password,
                'role_id' => 6
            ]);
            $res2 = Profile::create([
                'phone_number' => $request->input('phone_number'),
                'user_id' => $res->id,
                'province_id' => $request->input('province'),
                'expert_id' => $request->input('expert'),
            ]);
            DB::commit();
            if ($res && $res2) {
                return response()->json([
                    'data' => [
                        'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
                    ],
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'message' => 'خطا در ثبت اطلاعات' . $e->getMessage()
                ],
            ], 400);
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $supervisor_province = DB::table('users')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('experts', 'experts.id', '=', 'profiles.expert_id')
            ->leftJoin('provinces', 'provinces.id', '=', 'profiles.province_id')
            ->select('users.*', 'profiles.phone_number', 'roles.role_name', 'experts.name_expert'
                , 'provinces.province_name')
            ->where('users.role_id', '=', 6)
            ->where('users.id', '=', $id)
            ->paginate(10);
        if ($supervisor_province->count() == 0) {
            return response()->json([
                'message' => 'رکوردی مورد نظر یافت نشد'
            ]);
        } else {
            return response()->json(
                new SupervisorProvinceCollection($supervisor_province)
                , 200);
        }
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $supervisor_province = SupervisorProvince::query()->where('id', '=', $id)
            ->where('role_id', '=', 6)
            ->get();
        $profile = Profile::query()->where('user_id', '=', $id)->get();
        if ($supervisor_province->count() == 0 || $profile->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|min:3|max:255',
                'password' => 'nullable|string|min:8|max:255',
                'phone_number' => 'nullable|digits:11|regex:/(0)[0-9]{10}/|numeric|',
                'province' => 'required|numeric|exists:provinces,id',
                'expert' => 'nullable|numeric|exists:experts,id',
            ]);
            if ($request->has('password')) {
                try {
                    $password = Hash::make($request->input('password'));
                    DB::beginTransaction();
                    $supervisor_province->first()->update([
                        'name' => $request->input('name'),
                        'password' => $password,
                    ]);
                    $profile->first()->update([
                        'phone_number' => $request->input('phone_number'),
                        'province_id' => $request->input('province'),
                        'expert_id' => $request->input('expert'),
                    ]);
                    DB::commit();
                    return response()->json([
                        'data' => [
                            'message' => 'رکورد مورد نظر با موفقیت ویرایش شد'
                        ],
                    ], 201);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'data' => [
                            'message' => 'خطا در ویرایش اطلاعات'
                        ],
                    ], 400);
                }
            } else {
                try {
                    DB::beginTransaction();
                    $supervisor_province->first()->update([
                        'name' => $request->input('name'),
                    ]);
                    $profile->first()->update([
                        'phone_number' => $request->input('phone_number'),
                        'province_id' => $request->input('province'),
                        'expert_id' => $request->input('expert'),
                    ]);
                    DB::commit();
                    return response()->json([
                        'data' => [
                            'message' => 'رکورد مورد نظر با موفقیت ویرایش شد'
                        ],
                    ], 201);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'data' => [
                            'message' => 'خطا در ویرایش اطلاعات'
                        ],
                    ], 400);
                }
            }
        }
    }
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $supervisor_province = SupervisorProvince::query()
            ->where('id', '=', $id)
            ->where('role_id', '=', 6)
            ->get();
        if ($supervisor_province->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            try {
                DB::beginTransaction();
                $supervisor_province->first()->update([
                    'status' => 0,
                ]);
                Db::commit();
                return response()->json([
                    'data' => 'رکورد مورد نظر با موفقیت حذف شد'
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'خطا در حذف اطلاعات'
                ]);
            }
        }
    }

}
