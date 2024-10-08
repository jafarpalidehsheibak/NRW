<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorRequestCollection;
use App\Http\Resources\ContractorRequestResource;
use App\Http\Resources\RoadTypeCollection;
use App\Http\Resources\UserResource;
use App\Models\ContractorRequest;
use App\Models\ContractorRequestsCycle;
use App\Models\ContractPassword;
use App\Models\SafetyConsultant;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Sodium\add;

class ContractorRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['AuthAdminMiddleware'])->except('store','testjsonvalidate');
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
            ->where('role_id', '=', 3)
            ->get();
        $rnd = Str::random(6);
        $password = Hash::make($rnd);
        if (count($exist_on_user_tbl) == 0) {
            DB::beginTransaction();
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
                'password' => $password,

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
                'password' => $password,
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
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->where('users.id', '=', Auth::user()->id)
            ->select('profiles.province_id')
            ->get();
//        dd($province_id);
//        dd($province_id[0]->province_id);
        $res = DB::table('contractor_requests')
            ->join('provinces', 'contractor_requests.province_id', '=', 'provinces.id')
            ->join('cities', 'contractor_requests.city_id', '=', 'cities.id')
            ->join('experts', 'contractor_requests.expert_id', '=', 'experts.id')
            ->join('status_request', 'contractor_requests.status', '=', 'status_request.id')
            ->join('users', 'users.id', '=', 'contractor_requests.user_id')
            ->where('provinces.id', '=', $province_id[0]->province_id)
            ->where('status_request.id', '=', 1)
            ->select('contractor_requests.id', 'contractor_requests.contractor_name', 'contractor_requests.contractor_rank',
                'contractor_requests.user_id', 'contractor_requests.road_name', 'contractor_requests.workshop_location_kilometers', 'contractor_requests.workshop_begin_lat_long',
                'contractor_requests.workshop_end_lat_long', 'contractor_requests.workshop_name', 'contractor_requests.full_name_connector', 'contractor_requests.mobile_connector',
                'contractor_requests.email_connector', 'contractor_requests.approximate_start_date', 'contractor_requests.workshop_duration', 'contractor_requests.description',
                'contractor_requests.status',
                'status_request.status_name',
                'users.email',
                'provinces.province_name', 'cities.city_name', 'experts.name_expert')
            ->paginate(10);
        return response()->json(
            new ContractorRequestCollection($res)
            , 200);
    }



    public function contractor_request_road_id($id)
    {
        $res = DB::table('road_type')
            ->where('parent_id', $id)
            ->paginate(10);
        return response()->json(
            new RoadTypeCollection($res)
        );
    }
    public function road_type()
    {
        $res = DB::table('road_type')->where('parent_id', 0)->paginate(10);
        return response()->json(
            new RoadTypeCollection($res)
        );
    }

    public function contractor_request_road_importance(Request $request)
    {
        try {
            $road_id = $request->input('road_id');

            $contractor_request_id = $request->input('contractor_request_id');

            $res = DB::table('contractor_requests')
                ->where('id', $contractor_request_id)->first();

            $workshop_location_kilometers = $res->workshop_location_kilometers;
            if ($road_id == 1 || $road_id == 3) {
                $this->validate($request, [
                    'speed_befor' => 'required|numeric|min:1|max:200',
                    'speed_during' => 'required|numeric|min:1|max:200',
                ]);
                $speed_befor = $request->input('speed_befor');
                $speed_during = $request->input('speed_during');

                $t = ($workshop_location_kilometers / $speed_during) - ($workshop_location_kilometers / $speed_befor);
                $ContractorRequestItem = ContractorRequest::find($contractor_request_id);

                if ($t > 10) {
                    $updated_ContractorRequest = $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id,
                        't_delay_time' => $t
                    ]);
                    if ($updated_ContractorRequest) {
                        return response()->json([
                            'data' => [
                                'msg' => 'پروژه پر اهمیت است',
                                'flag' => 2,
                                't' => $t,
                                'contractor_request_id' => $contractor_request_id,
                            ]
                        ]);
                    }
                } elseif ($t < 10) {
                    $updated_ContractorRequest = $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id,
                        't_delay_time' => $t
                    ]);
                    if ($updated_ContractorRequest) {
                        return response()->json([
                            'data' => [
                                'msg' => 'پروژه کم اهمیت است',
                                'flag' => 3,
                                't' => $t,
                                'contractor_request_id' => $contractor_request_id
                            ]
                        ]);
                    }
                }
            } elseif ($road_id == 2) {
                $this->validate($request, [
                    'road_id2' => 'required',
                    'speed_befor' => 'required|numeric|min:1|max:200',
                    'speed_during' => 'required|numeric|min:1|max:200',
                    'volume' => 'required|numeric|min:1|max:100000',
                ]);
                $road_id2 = $request->input('road_id2');
                $res = DB::table('road_type')
                    ->where('id', $road_id2)->first();
                $vphpl = $res->vphpl;
                $volume = $request->input('volume');
                $speed_befor = $request->input('speed_befor');
                $speed_during = $request->input('speed_during');
                $hajm_zarfiyat = $volume / $vphpl;
                if ($hajm_zarfiyat >= 0.8) {
                    $ContractorRequestItem = ContractorRequest::find($contractor_request_id);
                    $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id2,
                        'volume' => $volume
                    ]);
                    return response()->json([
                        'data' => [
                            'msg' => 'پروژه پر اهمیت است',
                            'flag' => 2,
                            'hajm_zarfiyat' => $hajm_zarfiyat,
                            'contractor_request_id' => $contractor_request_id
                        ]
                    ]);
                } elseif ($hajm_zarfiyat < 0.8) {
                    $t = ($workshop_location_kilometers / $speed_during) - ($workshop_location_kilometers / $speed_befor);
                    $ContractorRequestItem = ContractorRequest::find($contractor_request_id);
                    $ContractorRequestItem->update([
                        'speed_befor' => $speed_befor,
                        'speed_during' => $speed_during,
                        'road_id_ref' => $road_id2,
                        't_delay_time' => $t
                    ]);
                    if ($t > 10) {
                        return response()->json([
                            'data' => [
                                'msg' => 'پروژه پر اهمیت است',
                                'flag' => 2,
                                't' => $t,
                                'contractor_request_id' => $contractor_request_id
                            ]
                        ]);
                    } elseif ($t < 10) {
                        $validator = Validator::make($request->all(), [
                            'abc' => 'required|numeric|in:1,2',
                        ]);
                        if ($validator->fails()) {
                            return response($validator->messages(), 200);
                        }
                        $abc = $request->input('abc');
                        $ContractorRequestItem->update([
                            'abc' => $abc,
                        ]);
                        if ($abc == 1) {
                            return response()->json([
                                'data' => [
                                    'msg' => 'پروژه پر اهمیت است',
                                    'flag' => 2,
                                    'abc' => 1,
                                    'contractor_request_id' => $contractor_request_id
                                ]
                            ]);
                        } elseif ($abc == 2) {
                            $validator = Validator::make($request->all(), [
                                'acd' => 'required|numeric|in:1,2',
                            ]);
                            if ($validator->fails()) {
                                return response($validator->messages(), 200);
                            }
                            $acd = $request->input('acd');
                            $ContractorRequestItem->update([
                                'acd' => $acd,
                            ]);
                            if ($acd == 1) {
                                return response()->json([
                                    'data' => [
                                        'msg' => 'پروژه پر اهمیت است',
                                        'flag' => 2,
                                        'acd' => 1,
                                        'contractor_request_id' => $contractor_request_id
                                    ]
                                ]);
                            } elseif ($acd == 2) {
                                return response()->json([
                                    'data' => [
                                        'msg' => 'پروژه کم اهمیت است',
                                        'flag' => 3,
                                        'acd' => 2,
                                        'contractor_request_id' => $contractor_request_id
                                    ]
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'اطلاعات ورودی نامعتبر است'
            ]);
        }
    }

    public function update_contractor_request_importance_status(Request $request)
    {
        $this->validate($request, [
            'flag' => 'required|numeric|in:2,3'
        ]);
        try {
            $contractor_request_id = $request->input('contractor_request_id');
            $ContractorRequestItem = ContractorRequest::find($contractor_request_id);
            $flag = $request->input('flag');
            if ($flag == 2) {
                try {
                    $rnd = Str::random(8);
                    $password = Hash::make($rnd);
                  $res1=   $ContractorRequestItem->update([
                      'status' => 2,
                      'password' =>$password,
                    ]);
                  $res2 = ContractPassword::create([
                      'contractor_request_id'=>$ContractorRequestItem->id,
                      'password'=>$rnd,
                  ]);
                    if($res1 && $res2){
                        return response()->json([
                            'data' => [
                                'msg' => 'اهمیت پروژه به پر اهمیت تغییر کرد',
                            ]
                        ]);
                    }
                }
                catch (\Exception $e)
                {
                    return response()->json([
                        'msg' => 'خطایی هنگام ثبت رخ داد . لطفا دوباره تلاش کنید'
                    ]);
                }
            }
            if ($flag == 3) {
                $ContractorRequestItem->update([
                    'status' => 3
                ]);
                return response()->json([
                    'data' => [
                        'msg' => 'اهمیت پروژه به کم اهمیت تغییر کرد',
                    ]
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'data' => [
                    'msg' => 'داده های ورودی نامعتبر است',
                ]
            ]);
        }
    }

    public function contract_show_one($id)
    {
        try {
            $contractor_request_id = $id;
            $res = DB::table('contractor_requests')
                ->join('provinces', 'contractor_requests.province_id', '=', 'provinces.id')
                ->join('cities', 'contractor_requests.city_id', '=', 'cities.id')
                ->join('experts', 'contractor_requests.expert_id', '=', 'experts.id')
                ->join('status_request', 'contractor_requests.status', '=', 'status_request.id')
                ->join('users', 'users.id', '=', 'contractor_requests.user_id')
                ->where('contractor_requests.id', '=', $contractor_request_id)
                ->select('contractor_requests.id', 'contractor_requests.contractor_name', 'contractor_requests.contractor_rank',
                    'contractor_requests.user_id', 'contractor_requests.road_name', 'contractor_requests.workshop_location_kilometers', 'contractor_requests.workshop_begin_lat_long',
                    'contractor_requests.workshop_end_lat_long', 'contractor_requests.workshop_name', 'contractor_requests.full_name_connector', 'contractor_requests.mobile_connector',
                    'contractor_requests.email_connector', 'contractor_requests.approximate_start_date', 'contractor_requests.workshop_duration', 'contractor_requests.description',
                    'contractor_requests.status',
                    'status_request.status_name',
                    'users.email',
                    'provinces.province_name', 'cities.city_name', 'experts.name_expert')->first();
//            dd($res);
            return response()->json(
                new ContractorRequestResource($res)
                , 200);
        } catch (\Exception $exception) {
            return response()->json([
                'data' => [
                    'msg' => 'داده های ورودی نامعتبر است',
                ]
            ]);
        }
    }

    public function testjsonvalidate(Request $request)
    {

        $json = $request->all();
//        $this->validate($request,[
//            'flag'=>'date_format:h'
//        ]);
//       dd($json['json']);

        $json_de = json_decode($json['json'], true);
//        dd($json_de);
        $rules = [
//            "executive_operation_correctly_contractor.true_false" => "required|in:13,14",
//            "executive_operation_correctly_contractor.description" => "nullable|string|min:3|max:1024",
//            "documentation_provided_contractor_consistent_executive.true_false" => 'required|in:13,14',
//            "documentation_provided_contractor_consistent_executive.description" => 'nullable|string|min:3|max:1024',
//            "sufficient_field_view_area.true_false" => 'required|in:13,14',
//            "sufficient_field_view_area.description" => 'nullable|string|min:3|max:1024',
//            "ttcp_scheme_capable_implemented.true_false" => 'required|in:13,14',
//            "ttcp_scheme_capable_implemented.description" => 'nullable|string|min:3|max:1024',
//            "border_middle_enough_space.true_false" => 'required|in:13,14',
//            "border_middle_enough_space.description" => 'nullable|string|min:3|max:1024',
//            "emergency_vehicles_access_executive.true_false" => 'required|in:13,14',
//            "emergency_vehicles_access_executive.description" => 'nullable|string|min:3|max:1024',
//            "necessary_measures_tmp_traffic.true_false" => 'required|in:13,14',
//            "necessary_measures_tmp_traffic.description" => 'nullable|string|min:3|max:1024',
//            "executive_operation_requirement_block_road.true_false" => 'required|in:13,14',
//            "executive_operation_requirement_block_road.description" => 'nullable|string|min:3|max:1024',
//            "pedestrians_motorcyclists_observed_enforcement.true_false" => 'required|in:13,14',
//            "pedestrians_motorcyclists_observed_enforcement.description" => 'nullable|string|min:3|max:1024',
//            "users_considered_executive_operations.true_false" => 'required|in:13,14',
//            "users_considered_executive_operations.description" => 'nullable|string|min:3|max:1024',
//            "intersecting_traffic_flow_vicinity.true_false" => 'required|in:13,14',
//            "intersecting_traffic_flow_vicinity.description" => 'nullable|string|min:3|max:1024',
//            "safety_measures_intersecting_traffic_vicinity.true_false" => 'required|in:13,14',
//            "safety_measures_intersecting_traffic_vicinity.description" => 'nullable|string|min:3|max:1024',
            //================================چک لیست خاتمه عملیات اجرایی و جمع آوری==========================================

//            "termination_date" => ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],
//            "end_time_hours" => ['required', 'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'],
//            "end_time_day" => "required|numeric|between:1,365",
//            "traffic_situation" => "required|string|min:3|max:1024",
//            "weather_conditions" => "required|string|min:3|max:1024",
//            "time_complete_cleaning" => ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],
//
            ///========================چک لیست درخواست شروع عملیات====================================================
//            "start_date"=> ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],
//            "start_time_hours"=> ['required', 'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'],
//            "start_time_day"=> "required|numeric|between:1,365",
//            "traffic_situation"=> "required|string|min:3|max:1024",
//            "weather_conditions"=> "required|string|min:3|max:1024",
            //======================چک لیست طرح اطلاع رسانی عمومی (PIP)==============================================
//            "public_information_arrangements.0.newspaper_advertisements_notices.done_notdone" => "required|in:5,6",
//            "public_information_arrangements.0.providing_information_through_website.provided_notprovided" => "required|in:7,8",
//            "public_information_arrangements.0.brochures.provided_notprovided" => "required|in:7,8",
//            "public_information_arrangements.0.variable_message_boards.provided_notprovided" => "required|in:7,8",
//            "public_information_arrangements.0.comprehensive_radio_television_social.done_notdone" => "required|in:5,6",
//            "public_information_arrangements.0.traffic_radio.done_notdone" => "required|in:5,6",
//            "public_information_arrangements.0.system_141.done_notdone" => "required|in:5,6",
//            "public_information_arrangements.0.contact_information_boards.provided_notprovided" => "required|in:7,8",
//            "details_notification_messages.0.apology_text_users.done_notdone" => "required|in:5,6",
//            "details_notification_messages.0.time_start_executive_operation.yes_no" => "required|in:1,2",
//            "details_notification_messages.0.end_time_executive_operation.yes_no" => "required|in:1,2",
//            "details_notification_messages.0.type_executive_operation.yes_no" => "required|in:1,2",
//            "details_notification_messages.0.recommended_transit_speed.done_notdone" => "required|in:5,6",
//            "details_notification_messages.0.special_advice_necessary.done_notdone" => "required|in:5,6",
//            "details_notification_messages.0.traffic_changes.yes_no" => "required|in:1,2",
//            "details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no" => "required|in:1,2",
//            "details_notification_messages.0.contact_numbers_administrators_trustees_question.0.person" =>
//                "nullable|required_if:details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1|string|min:3|max:1024",
//            "details_notification_messages.0.contact_numbers_administrators_trustees_question.0.position" =>
//                "nullable|required_if:details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1|string|min:3|max:1024",
//            "details_notification_messages.0.contact_numbers_administrators_trustees_question.0.phone_number" =>
//                ['nullable', 'required_if:details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1','regex:/(09)[0-9]{9}/'],
//            "details_notification_messages.0.contact_number_emergency_services.yes_no" => "required|in:1,2",
            //===============================================چک لیست طرح عملیات حمل و نقل TOP ================================
//            "provision_parking_arrangements.provided_notprovided" => "required|in:7,8",
//            "provision_public_shared_vehicles.provided_notprovided" => "required|in:7,8",
//            "provision_special_lanes_passenger.provided_notprovided" => "required|in:7,8",
//            "arrangements_convergence_lines.done_notdone" => "required|in:5,6",
//            "measures_reduce_traffic_congestion.done_notdone" => "required|in:5,6",
//            "using_intelligent_systems.yes_no" => "required|in:7,8",
//            "creating_overtaking_lanes_heavy_vehicles.created_notcreated" => "required|in:15,16",
//            "creation_special_lanes_heavy_vehicles.created_notcreated" => "required|in:15,16",
//            "coordination_adjacent_operational_areas.coordinate_notcoordinate" => "required|in:17,18,19",
//            "stop_limits.provided_notprovided" => "required|in:7,8",
//            "control_railway_crossings.provided_notprovided" => "required|in:7,8",
//            "ramp_control.provided_notprovided" => "required|in:7,8",
//            "reducing_permitted_speed_using_signs.used_notused" => "required|in:11,12",
//            "modification_intersections_passages.done_notdone" => "required|in:5,6",
//            "restriction_heavy_vehicles_trucks.done_notdone" => "required|in:5,6",
//            "circulation_restrictions.provided_notprovided" => "required|in:7,8",
//            "personnel_safety_training.used_notused" => "required|in:11,12",
//            "coordination_response_emergency_services.provided_notprovided" => "required|in:7,8",
//            "surveillance_cctv_cameras.used_notused" => "required|in:11,12",
//            "patrol.used_notused" => "required|in:11,12",
//            "law_enforcement_police.used_notused" => "required|in:11,12",
//            "access_rescue_equipment_emergency.used_notused" => "required|in:11,12",
//            "presence_someone_emergency_services.used_notused" => "required|in:11,12",
//            "emergency_vehicle_executive_operation.provided_notprovided" => "required|in:7,8",
//            "ensuring_safety_pedestrians_users.provided_notprovided" => "required|in:7,8",
//            "safe_accesses_workshop.provided_notprovided" => "required|in:7,8",
//            "incident_management_program_workshop.provided_notprovided" => "required|in:7,8",
//            "detour_options.exist_notexist" => "required|in:19,20",
//            "necessary_arrangements_maintaining_detour_route.provided_notprovided" => "required|in:7,8",
//            "ttcp_configuration_considerations_minimize_traffic.provided_notprovided" => "required|in:7,8",
//            "preparedness_deal_unplanned_events.provided_notprovided" => "required|in:7,8",
            'consultant_name' => 'required|string|min:3|max:255',
            'type_road' => 'required|numeric|exists:road_type|in:1,2,3',
            'number_of_lines' => 'required|numeric|in:1,2,3,4,5,6,7',
            'width_crossing_line' => 'required|numeric|min:1|max:1000',
            'road_classification' => 'required|in:25,26,27',
            'country_division_road' => 'required|in:28,29,30',
            'road_speed_limit' => 'required|numeric|min:1|max:200',
            'type_area_terms_road_type.id_res' => 'required|numeric',
            'type_area_terms_road_type.id_value' => 'required|string',
            'details_of_plan.0.length_activity_area_M' => 'required|numeric',
            'details_of_plan.0.length_transition_zone_T' => 'required|numeric',
            'details_of_plan.0.length_preconscious_area_A' => 'required|numeric',
            'details_of_plan.0.length_free_range_entry_L' => 'required|numeric',
            'details_of_plan.0.length_free_range_exit_G' => 'required|numeric',
            'details_of_plan.0.free_range_width_S' => 'required|numeric',
            'details_of_plan.0.length_termination_area_E' => 'required|numeric',
            'details_of_plan.0.distance_panel_1' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_2' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_3' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_4' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_5' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_6' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_7' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_8' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_9' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_10' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_11' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_12' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_13' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_14' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_15' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_16' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_17' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_18' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_19' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_20' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_21' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_22' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_23' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_24' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_25' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_26' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_27' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_28' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_29' => 'nullable|numeric',
            'details_of_plan.0.distance_panel_30' => 'nullable|numeric',
            'traffic_control_method' => 'required|string',
            'type_traffic_protection.new_jersey' => 'required|in:13,14',
            'type_traffic_protection.cone.0.cone_bool' => 'required|in:13,14',
            'type_traffic_protection.cone.0.distance_cones_transition_zone' => 'nullable|required_if:type_traffic_protection.cone.0.cone_bool,==,13',
            'type_traffic_protection.cone.0.distance_cones_straight_area' => 'nullable|required_if:type_traffic_protection.cone.0.cone_bool,==,13',
            'type_traffic_protection.cone.0.distance_cones_along_inlet' => 'nullable|required_if:type_traffic_protection.cone.0.cone_bool,==,13',
            'type_traffic_protection.cone.0.distance_cones_along_outlet' => 'nullable|required_if:type_traffic_protection.cone.0.cone_bool,==,13',
            'type_traffic_protection.barrel.0.barrel_bool' => 'required|in:13,14',
            'type_traffic_protection.barrel.0.distance_barrels_transition_zone' => 'nullable|required_if:type_traffic_protection.barrel.0.barrel_bool,==,13',
            'type_traffic_protection.barrel.0.distance_barrels_straight_area' => 'nullable|required_if:type_traffic_protection.barrel.0.barrel_bool,==,13',
            'type_traffic_protection.barrel.0.distance_barrels_along_inlet' => 'nullable|required_if:type_traffic_protection.barrel.0.barrel_bool,==,13',
            'type_traffic_protection.barrel.0.distance_barrels_along_outlet' => 'nullable|required_if:type_traffic_protection.barrel.0.barrel_bool,==,13',
            'number_blocked_lines' => 'required|integer|min:1|max:1000',
            'night_operation.have_nothave' => 'required|in:9,10',
            'obstruction_of_ramp.have_nothave' => 'required|in:9,10',
            'intersection_obstruction.have_nothave' => 'required|in:9,10',
            'blockage_time_during_week' => 'required|in:31,32,33',
            'signs_equipment_operation_area.210_210_400.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.210_210_400.210_210_400_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.210_210_400.have_nothave,9',
            'signs_equipment_operation_area.210_210_600.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.210_210_600.210_210_600_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.210_210_600.have_nothave,9',
            'signs_equipment_operation_area.210_210_800.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.210_210_800.210_210_800_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.210_210_800.have_nothave,9',
            'signs_equipment_operation_area.180_180_drive_right.180_180_drive_right_value' => 'nullable|numeric',
            'signs_equipment_operation_area.180_180_drive_left.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.180_180_drive_left.180_180_drive_left_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.180_180_drive_left.have_nothave,9',
            'signs_equipment_operation_area.90_title.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.90_title.90_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.90_title.have_nothave,9',
            'signs_equipment_operation_area.100_100_30.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.100_100_30.100_100_30_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.100_100_30.have_nothave,9',
            'signs_equipment_operation_area.100_100_50.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.100_100_50.100_100_50_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.100_100_50.have_nothave,9',
            'signs_equipment_operation_area.120_120_80.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.120_120_80.120_120_80_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.120_120_80.have_nothave,9',
            'signs_equipment_operation_area.100_100_1.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.100_100_1.100_100_1_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.100_100_1.have_nothave,9',
            'signs_equipment_operation_area.100_100_2.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.100_100_2.100_100_2_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.100_100_2.have_nothave,9',
            'signs_equipment_operation_area.100_310_end.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.100_310_end.100_310_end_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.100_310_end.have_nothave,9',
            'signs_equipment_operation_area.hagh_taghadom.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.hagh_taghadom.hagh_taghadom_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.hagh_taghadom.have_nothave,9',
            'signs_equipment_operation_area.hagh_taghadom_vasile.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.hagh_taghadom_vasile.hagh_taghadom_vasile_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.hagh_taghadom_vasile.have_nothave,9',
            'signs_equipment_operation_area.50_75_1500.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.50_75_1500.50_75_1500_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.50_75_1500.have_nothave,9',
            'signs_equipment_operation_area.50_75_1000.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.50_75_1000.50_75_1000_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.50_75_1000.have_nothave,9',
            'signs_equipment_operation_area.50_75_500.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.50_75_500.50_75_500_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.50_75_500.have_nothave,9',
            'signs_equipment_operation_area.150_kargar_mashghool.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.150_kargar_mashghool.150_kargar_mashghool_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.150_kargar_mashghool.have_nothave,9',
            'signs_equipment_operation_area.50_75_road_narrow.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.50_75_road_narrow.50_75_road_narrow_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.50_75_road_narrow.have_nothave,9',
            'signs_equipment_operation_area.75_danger_light.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.75_danger_light.75_danger_light_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.75_danger_light.have_nothave,9',
            'signs_equipment_operation_area.traffic_light.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.traffic_light.traffic_light_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.traffic_light.have_nothave,9',
            'signs_equipment_operation_area.lamp_makhrooti.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.lamp_makhrooti.lamp_makhrooti_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.lamp_makhrooti.have_nothave,9',
            'signs_equipment_operation_area.borj_noor_mobile.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.borj_noor_mobile.borj_noor_mobile_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.borj_noor_mobile.have_nothave,9',
            'signs_equipment_operation_area.newjersi_terrafici.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.newjersi_terrafici.newjersi_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.newjersi_terrafici.have_nothave,9',
            'signs_equipment_operation_area.boshke_zard.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.boshke_zard.boshke_zard_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.boshke_zard.have_nothave,9',
            'signs_equipment_operation_area.makhrooti_terrafic.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.makhrooti_terrafic.makhrooti_terrafic_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.makhrooti_terrafic.have_nothave,9',
            'signs_equipment_operation_area.adamak_terrafici.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.adamak_terrafici.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.adamak_terrafici.have_nothave,9',
            'signs_equipment_operation_area.kolah_imeni_cheraghdar.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.kolah_imeni_cheraghdar.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.kolah_imeni_cheraghdar.have_nothave,9',
            'signs_equipment_operation_area.shabname_terrafici.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.shabname_terrafici.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.shabname_terrafici.have_nothave,9',
            'signs_equipment_operation_area.kamiyoon_tajhizat_terrafici.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.kamiyoon_tajhizat_terrafici.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.kamiyoon_tajhizat_terrafici.have_nothave,9',
            'signs_equipment_operation_area.tabloo_kamiyoon.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.tabloo_kamiyoon.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.tabloo_kamiyoon.have_nothave,9',
            'signs_equipment_operation_area.khatkeshi_zard_zebra.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.khatkeshi_zard_zebra.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.khatkeshi_zard_zebra.have_nothave,9',
            'signs_equipment_operation_area.soratgir.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.soratgir.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.soratgir.have_nothave,9',
            'signs_equipment_operation_area.khatkeshi_zard_zebra.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.khatkeshi_zard_zebra.adamak_terrafici_value' => 'nullable|numeric|required_if:signs_equipment_operation_area.khatkeshi_zard_zebra.have_nothave,9',


            'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave' => 'required|in:9,10',
            'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.jadde_enherafi_type_color' => 'nullable|string|required_if:signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave,9',
            'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.jadde_enherafi_company_color' => 'nullable|string|required_if:signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave,9',

        ];
        $validator = Validator::make($json_de, $rules);
//        dd($validator);
        if ($validator->passes()) {
            return "ok";
        } else if ($validator->fails()) {
            return response($validator->messages(), 200);
        }
    }

    public function validation_proc($val_name = [], $type = [])
    {
        $ruls_moj = [];

        foreach ($val_name as $key => $val) {
            $id_type = $type[$key];

            if ($id_type == 1) {
                $rules[0] = [
                    $val =>
                        ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/']
                ];
                $ruls_moj += $rules[0];

            } elseif ($id_type == 2) {
                $rules[1] = [
                    $val =>
                        ['required', 'numeric']
                ];
                $ruls_moj += $rules[1];


            } elseif ($id_type == 3) {
                $rules[2] = [
                    $val =>
                        ['required', 'date']
                ];
                $ruls_moj += $rules[2];
            }
        }

        return $ruls_moj;

    }




}
