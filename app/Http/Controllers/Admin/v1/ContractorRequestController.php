<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorRequestCollection;
use App\Http\Resources\ContractorRequestResource;
use App\Http\Resources\RoadTypeCollection;
use App\Http\Resources\UserResource;
use App\Models\ContractorRequest;
use App\Models\SafetyConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContractorRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','ShowContractorRequestMiddleware'])->except('store');
    }
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
            'approximate_start_date' => 'required|numeric|min_digits:10|max_digits:10|between:1706028890,2021635490',
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
                'role_id' => 3 //role 3 = پیمانکار
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
                'status' => 1, // status = 1 => یعنی ثبت شده و در انتظار تایید
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
                'status' => 1, // status = 1 => یعنی ثبت شده و در انتظار تایید
            ]);
            if ($res) {
                return response()->json([
                    'data' => [
                        'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
                    ],
                ], 201);
            }
        }

    }

    public function show()
    {
//        return response()->json([
//            'name'=>auth('api')->user()->role_id
//        ]);
//       dd(auth('api')->user()->id);
        $province_id = DB::table('users')
            ->join('profiles','profiles.user_id','=','users.id')
            ->where('users.id','=',auth('api')->user()->id)
            ->select('profiles.province_id')
            ->get();
//        dd($province_id);
//        dd($province_id[0]->province_id);
        $res = DB::table('contractor_requests')
            ->join('provinces','contractor_requests.province_id','=','provinces.id')
            ->join('cities','contractor_requests.city_id','=','cities.id')
            ->join('experts','contractor_requests.expert_id','=','experts.id')
            ->join('status_request','contractor_requests.status','=','status_request.id')
            ->join('users','users.id','=','contractor_requests.user_id')
            ->where('provinces.id','=',$province_id[0]->province_id)
            ->select('contractor_requests.id','contractor_requests.contractor_name','contractor_requests.contractor_rank',
                'contractor_requests.user_id','contractor_requests.road_name','contractor_requests.workshop_location_kilometers','contractor_requests.workshop_begin_lat_long',
                'contractor_requests.workshop_end_lat_long','contractor_requests.workshop_name','contractor_requests.full_name_connector','contractor_requests.mobile_connector',
                'contractor_requests.email_connector','contractor_requests.approximate_start_date','contractor_requests.workshop_duration','contractor_requests.description',
                'contractor_requests.status',
                'status_request.status_name',
                'users.email',
                'provinces.province_name','cities.city_name','experts.name_expert')
            ->paginate(10);
        return response()->json(
            new ContractorRequestCollection($res)
        ,200);
    }

    public function contractor_request_road()
    {
        $res = DB::table('road_type')->where('parent_id',0)->paginate(10);
        return response()->json(
            new RoadTypeCollection($res)
        );
    }
    public function contractor_request_road_id($id)
    {
        $id = Crypt::decrypt($id);
        $res = DB::table('road_type')
            ->where('parent_id',$id)
            ->paginate(10);
        return response()->json(
            new RoadTypeCollection($res)
        );
    }

    public function contractor_request_road_importance(Request $request)
    {
        try {
            $road_id = $request->input('road_id');
            $road_id = Crypt::decrypt($road_id);

            $contractor_request_id = $request->input('contractor_request_id');
            $contractor_request_id = Crypt::decrypt($contractor_request_id);

            $res = DB::table('contractor_requests')
                ->where('id',$contractor_request_id)->first();

            $workshop_location_kilometers = $res->workshop_location_kilometers;
            if ($road_id==1 || $road_id==3){
                $this->validate($request,[
                    'speed_befor'=>'required|numeric|min:1|max:200',
                    'speed_during'=>'required|numeric|min:1|max:200',
                ]);
                $speed_befor = $request->input('speed_befor');
                $speed_during = $request->input('speed_during');

                $t  = ($workshop_location_kilometers / $speed_during) - ($workshop_location_kilometers / $speed_befor);
                $ContractorRequestItem = ContractorRequest::find($contractor_request_id);

                if ($t > 10 ){
                    $updated_ContractorRequest = $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id,
                        't_delay_time'=>$t
                    ]);
                    if ($updated_ContractorRequest) {
                        return response()->json([
                            'data'=>[
                                'msg'=>'پروژه پر اهمیت است',
                                'flag'=>2,
                                't'=>$t,
                                'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                            ]
                        ]);
                    }
                }
                elseif($t < 10 )
                {
                    $updated_ContractorRequest = $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id,
                        't_delay_time'=>$t
                    ]);
                    if ($updated_ContractorRequest) {
                        return response()->json([
                            'data'=>[
                                'msg'=>'پروژه کم اهمیت است',
                                'flag'=>3,
                                't'=>$t,
                                'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                            ]
                        ]);
                    }
                }
            }
            elseif ($road_id==2){
                 $this->validate($request,[
                    'road_id2'=>'required',
                     'speed_befor'=>'required|numeric|min:1|max:200',
                     'speed_during'=>'required|numeric|min:1|max:200',
                     'volume'=>'required|numeric|min:1|max:100000',
                ]);
                $road_id2 = $request->input('road_id2');
                $road_id2 = Crypt::decrypt($road_id2);
                $res = DB::table('road_type')
                    ->where('id',$road_id2)->first();
                $vphpl = $res->vphpl;
                $volume = $request->input('volume');
                $speed_befor = $request->input('speed_befor');
                $speed_during = $request->input('speed_during');
                $hajm_zarfiyat = $volume / $vphpl;
                if($hajm_zarfiyat >= 0.8){
                    $ContractorRequestItem = ContractorRequest::find($contractor_request_id);
                     $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id2,
                        'volume'=>$volume
                    ]);
                    return response()->json([
                        'data'=>[
                            'msg'=>'پروژه پر اهمیت است',
                            'flag'=>2,
                            'hajm_zarfiyat'=>$hajm_zarfiyat,
                            'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                        ]
                    ]);
                }
                elseif($hajm_zarfiyat < 0.8){
                    $t  = ($workshop_location_kilometers / $speed_during) - ($workshop_location_kilometers / $speed_befor);
                    $ContractorRequestItem = ContractorRequest::find($contractor_request_id);
                    $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id2,
                        't_delay_time'=>$t
                    ]);
                    if ($t > 10 ){
                            return response()->json([
                                'data'=>[
                                    'msg'=>'پروژه پر اهمیت است',
                                    'flag'=>2,
                                    't'=>$t,
                                    'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                                ]
                            ]);
                    }
                    elseif($t < 10 )
                    {
                        $validator = Validator::make($request->all(),[
                            'abc' => 'required|numeric|in:1,2',
                        ]);
                        if($validator->fails()){
                            return response($validator->messages(), 200);
                        }
                        $abc = $request->input('abc');
                        $ContractorRequestItem->update([
                            'abc' => $abc,
                        ]);
                        if ($abc==1){
                            return response()->json([
                                'data'=>[
                                    'msg'=>'پروژه پر اهمیت است',
                                    'flag'=>2,
                                    'abc'=>1,
                                    'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                                ]
                            ]);
                        }
                        elseif ($abc==2){
                            $validator = Validator::make($request->all(),[
                                'acd' => 'required|numeric|in:1,2',
                            ]);
                            if($validator->fails()){
                                return response($validator->messages(), 200);
                            }
                            $acd = $request->input('acd');
                            $ContractorRequestItem->update([
                                'acd' => $acd,
                            ]);
                            if ($acd==1){
                                return response()->json([
                                    'data'=>[
                                        'msg'=>'پروژه پر اهمیت است',
                                        'flag'=>2,
                                        'acd'=>1,
                                        'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                                    ]
                                ]);
                            }
                            elseif ($acd==2){
                                return response()->json([
                                    'data'=>[
                                        'msg'=>'پروژه کم اهمیت است',
                                        'flag'=>3,
                                        'acd'=>2,
                                        'contractor_request_id'=>Crypt::encrypt($contractor_request_id)
                                    ]
                                ]);
                            }
                        }
                    }

                }
            }
        }
        catch (\Exception $exception){
            return response()->json([
                'msg'=>'اطلاعات ورودی نامعتبر است'
            ]);
        }
    }

    public function update_contractor_request_importance_status(Request $request)
    {
        $this->validate($request,[
            'flag'=>'required|numeric|in:2,3'
        ]);
        try {
            $contractor_request_id = $request->input('contractor_request_id');
            $contractor_request_id = Crypt::decrypt($contractor_request_id);

            $ContractorRequestItem = ContractorRequest::find($contractor_request_id);

            $flag = $request->input('flag');
            if ($flag==2)
            {
                $ContractorRequestItem->update([
                    'status'=>2
                ]);
                return response()->json([
                    'data'=>[
                        'msg'=>'اهمیت پروژه به پر اهمیت تغییر کرد',
                    ]
                ]);
            }
            if ($flag==3)
            {
                $ContractorRequestItem->update([
                    'status'=>3
                ]);
                return response()->json([
                    'data'=>[
                        'msg'=>'اهمیت پروژه به کم اهمیت تغییر کرد',
                    ]
                ]);
            }
        }
        catch (\Exception $exception){
            return response()->json([
                'data'=>[
                    'msg'=>'داده های ورودی نامعتبر است',
                ]
            ]);
        }
    }

    public function contract_show_one($id)
    {
        try {
            $contractor_request_id = Crypt::decrypt($id);
            $res = DB::table('contractor_requests')
                ->join('provinces','contractor_requests.province_id','=','provinces.id')
                ->join('cities','contractor_requests.city_id','=','cities.id')
                ->join('experts','contractor_requests.expert_id','=','experts.id')
                ->join('status_request','contractor_requests.status','=','status_request.id')
                ->where('contractor_requests.id','=',$contractor_request_id)
                ->select('contractor_requests.id','contractor_requests.contractor_name','contractor_requests.contractor_rank',
                    'contractor_requests.user_id','contractor_requests.road_name','contractor_requests.workshop_location_kilometers','contractor_requests.workshop_begin_lat_long',
                    'contractor_requests.workshop_end_lat_long','contractor_requests.workshop_name','contractor_requests.full_name_connector','contractor_requests.mobile_connector',
                    'contractor_requests.email_connector','contractor_requests.approximate_start_date','contractor_requests.workshop_duration','contractor_requests.description',
                    'contractor_requests.status',
                    'status_request.status_name',
                    'provinces.province_name','cities.city_name','experts.name_expert')->first();
            return response()->json(
                new ContractorRequestResource($res)
                ,200);
        }
        catch (\Exception $exception){
            return response()->json([
                'data'=>[
                    'msg'=>'داده های ورودی نامعتبر است',
                ]
            ]);
        }


    }
}
