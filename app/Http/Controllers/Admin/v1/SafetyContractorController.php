<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SafetyContractorResource;
use App\Http\Resources\SafetyContratctorCollection;
use App\Models\Profile;
use App\Models\SafetyContractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SafetyContractorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
//        $safety_contractor = SafetyContractor::where('role_id', '=', 5)->paginate(10);
        $safety_contractor = DB::table('users')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('experts', 'experts.id', '=', 'profiles.expert_id')
            ->select('users.*', 'profiles.phone_number','profiles.address', 'roles.role_name', 'experts.name_expert', 'experts.id as expertId')
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->paginate(10);
        return response()->json(
            new SafetyContratctorCollection($safety_contractor)
            , 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|digits:11|numeric|regex:/(0)[0-9]{10}/|unique:users',
            'password' => 'required|string|min:8|max:255',
            'phone_number' => 'nullable|digits:11|regex:/(0)[0-9]{10}/|numeric|',
            'expert' => 'nullable|numeric|exists:experts,id',
            'address' => 'nullable|string|min:3|max:255',
        ]);
        try {
            DB::beginTransaction();
            $password = Hash::make($request->input('password'));
            $res = SafetyContractor::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $password,
                'role_id' => 5
            ]);
            $res2 = Profile::create([
                'phone_number' => $request->input('phone_number'),
                'user_id' => $res->id,
                'expert_id' => $request->input('expert'),
                'address' => $request->input('address'),
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
                    'message' => 'خطا در ثبت اطلاعات'
                ],
            ], 400);
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $safety_contractor = SafetyContractor::query()
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('experts', 'experts.id', '=', 'profiles.expert_id')
            ->select('users.*', 'profiles.phone_number', 'roles.role_name', 'experts.name_expert')
            ->where('users.role_id', '=', 5)
            ->where('users.id', '=', $id)
            ->orderBy('id','desc')
            ->paginate(10);
        if ($safety_contractor->count() == 0) {
            return response()->json([
                'message' => 'رکوردی مورد نظر یافت نشد'
            ]);
        } else {
            return response()->json([
                'data' => [
                    new SafetyContratctorCollection($safety_contractor)
                ]
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $safety_contractor = SafetyContractor::query()->where('id', '=', $id)
            ->where('role_id', '=', 5)
            ->get();
        $profile = Profile::query()->where('user_id', '=', $id)->get();
        if ($safety_contractor->count() == 0 || $profile->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|min:3|max:255',
                'password' => 'nullable|string|min:8|max:255',
                'phone_number' => 'nullable|digits:11|regex:/(0)[0-9]{10}/|numeric|',
                'expert' => 'nullable|numeric|exists:experts,id',
                'address' => 'nullable|string|min:3|max:255',
            ]);
            if ($request->has('password')) {
                try {
                    $password = Hash::make($request->input('password'));
                    DB::beginTransaction();
                    $safety_contractor->first()->update([
                        'name' => $request->input('name'),
                        'password' => $password,
                    ]);
                    $profile->first()->update([
                        'phone_number' => $request->input('phone_number'),
                        'expert_id' => $request->input('expert'),
                        'address' => $request->input('address'),
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
                    $safety_contractor->first()->update([
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                    ]);
                    $profile->first()->update([
                        'phone_number' => $request->input('phone_number'),
                        'expert_id' => $request->input('expert'),
                        'address' => $request->input('address'),
                    ]);
                    DB::commit();
                    return response()->json([
                        'data' => 'رکورد مورد نظر با موفقیت ویرایش شد'
                    ], 201);
                }
                catch (\Exception $e) {
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
        $safety_contractor = SafetyContractor::query()
            ->where('id', '=', $id)
            ->where('role_id', '=', 5)
            ->get();
        if ($safety_contractor->count() == 0) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $safety_contractor->first()->delete();
            return response()->json([
                'data' => 'رکورد مورد نظر با موفقیت حذف شد'
            ], 200);
        }
    }

}
