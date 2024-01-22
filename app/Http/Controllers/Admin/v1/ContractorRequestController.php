<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\ContractorRequest;
use App\Models\SafetyConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContractorRequestController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'contractor_name' => 'required|string|min:3|max:255',
            'contractor_rank' => 'required|numeric|between:1,5',
            'contractor_mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'province_id' => 'required|numeric|exists:provinces,id',
            'city_id' => 'required|numeric|exists:cities,id',
            'road_name' => 'required|string|min:3|max:255',
            'expert_id' => 'required|numeric|exists:experts,id',
            'workshop_location_kilometers' => 'required|numeric',
            'workshop_begin_lat_long' => 'required|string|min:3|max:255',
            'workshop_end_lat_long' => 'required|string|min:3|max:255',
            'workshop_name' => 'required|string|min:3|max:255',
            'full_name_connector' => 'required|string|min:3|max:255',
            'mobile_connector' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'email_connector' => 'nullable|email',
            'approximate_start_date' => 'required|date',
            'workshop_duration' => 'required|numeric|between:1,1000',
            'description' => 'nullable|string|min:3|max:255',
        ]);
        $exist_on_user_tbl = DB::table('users')
            ->where('email', '=', $request->input('contractor_mobile'))
            ->where('role_id','=',3)
            ->get();
        if (count($exist_on_user_tbl) == 0) {
            DB::beginTransaction();
            $rnd = Str::random(16);
            $password = Hash::make($rnd);
            $res2 = SafetyConsultant::create([
                'name' => $request->input('contractor_name'),
                'email' => $request->input('contractor_mobile'),
                'password' => $password,
                'role_id' => 3
            ]);
            $res = ContractorRequest::create([
                'contractor_name' => $request->input('contractor_name'),
                'contractor_rank' => $request->input('contractor_rank'),
                'user_id' => $res2->id,
                'province_id' => $request->input('province_id'),
                'city_id' => $request->input('city_id'),
                'road_name' => $request->input('road_name'),
                'expert_id' => $request->input('expert_id'),
                'workshop_location_kilometers' => $request->input('workshop_location_kilometers'),
                'workshop_begin_lat_long' => $request->input('workshop_begin_lat_long'),
                'workshop_end_lat_long' => $request->input('workshop_end_lat_long'),
                'workshop_name' => $request->input('workshop_name'),
                'full_name_connector' => $request->input('full_name_connector'),
                'mobile_connector' => $request->input('mobile_connector'),
                'email_connector' => $request->input('email_connector'),
                'approximate_start_date' => $request->input('approximate_start_date'),
                'workshop_duration' => $request->input('workshop_duration'),
                'description' => $request->input('description'),
                'status' => 0,
            ]);
            DB::commit();
            if ($res && $res2) {
                return response()->json([
                    'data' => [
                        'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
                    ],
                ], 201);
            }
        } elseif (count($exist_on_user_tbl) > 0) {
            $res = ContractorRequest::create([
                'contractor_name' => $request->input('contractor_name'),
                'contractor_rank' => $request->input('contractor_rank'),
                'user_id' => $exist_on_user_tbl[0]->id,
                'province_id' => $request->input('province_id'),
                'city_id' => $request->input('city_id'),
                'road_name' => $request->input('road_name'),
                'expert_id' => $request->input('expert_id'),
                'workshop_location_kilometers' => $request->input('workshop_location_kilometers'),
                'workshop_begin_lat_long' => $request->input('workshop_begin_lat_long'),
                'workshop_end_lat_long' => $request->input('workshop_end_lat_long'),
                'workshop_name' => $request->input('workshop_name'),
                'full_name_connector' => $request->input('full_name_connector'),
                'mobile_connector' => $request->input('mobile_connector'),
                'email_connector' => $request->input('email_connector'),
                'approximate_start_date' => $request->input('approximate_start_date'),
                'workshop_duration' => $request->input('workshop_duration'),
                'description' => $request->input('description'),
                'status' => 0,
            ]);
            if ($res) {
                return response()->json([
                    'data' => [
                        'message' => 'شما قبلا به عنوان پیمانکار ثبت نام کرده اید . لطفا از پنل پیمانکار اقدام به ثبت درخواست نمایید'
                    ],
                ], 201);
            }
        }

    }
}
