<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\ContractorRequest;
use App\Models\ContractorRequestsCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware(['AuthUserMiddleware']);
    }
    public function checklist_all_request(Request $request)
    {
        $contractor_request_id = $request->input('contractor_request_id');
        $checklist_id = $request->input('checklist_id');
        $user_id = $request->input('user_id');
        $user_id = Crypt::decrypt($user_id);
        $js = $request->all();
        $json_test = json_decode($js["checklist_item_detail_id"], true);
        $arr = array(
            'contractor_request_id' => $contractor_request_id,
            'checklist_id' => $checklist_id,
            'user_id' => $user_id,
            'checklist_item_detail_id' => $json_test
        );
        $json_de = json_encode($arr, true);
        $rules = $this->checklist($checklist_id);
        if (!$rules) {
            return response()->json(
                [
                    'message' => 'آی دی چک لیست معتبر نمی باشد'
                ]
            );
        }
        $json_de = json_decode($json_de, true);

        $validator = Validator::make(
            $json_de, $rules
        );
        $json_test = json_encode($json_test, true);

        if ($validator->passes()) {
            $contractor_request = ContractorRequest::find($contractor_request_id);
            $exist_contract_request_checklist =
                ContractorRequestsCycle::query()
                    ->where('contractor_request_id','=',$contractor_request_id)
                    ->where('checklist_id','=',$checklist_id)
                    ->first();
            if (is_null($exist_contract_request_checklist)){
                if ($checklist_id==4){
                    ContractorRequestsCycle::create([
                        'contractor_request_id' => $contractor_request_id,
                        'user_id' => $user_id,
                        'checklist_id' => $checklist_id,
                        'checklist_item_detail_id' =>$json_test
                    ]);
                    $contractor_request->update([
                        'status'=>5
                    ]);
                }
                elseif ($checklist_id==5){
                    ContractorRequestsCycle::create([
                        'contractor_request_id' => $contractor_request_id,
                        'user_id' => $user_id,
                        'checklist_id' => $checklist_id,
                        'checklist_item_detail_id' =>$json_test
                    ]);
                    $contractor_request->update([
                        'status'=>6
                    ]);
                }
                return response()->json([
                    'data' => [
                        'message' => 'رکورد مورد نظر با موفقیت ایجاد شد'
                    ],
                ], 201);


            }
            elseif (!is_null($exist_contract_request_checklist))
            {
                $res1 =  $exist_contract_request_checklist->update([
                    'contractor_request_id' => $contractor_request_id,
                    'user_id' => $user_id,
                    'checklist_id' => $checklist_id,
                    'checklist_item_detail_id' =>$json_test
                ]);
                if ($res1) {
                    return response()->json([
                        'data' => [
                            'message' => 'رکورد مورد نظر با موفقیت ویرایش شد'
                        ],
                    ], 201);
                }
            }


        } else if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
    }

    public function checklist($checklist_id)
    {
        if ($checklist_id == 1) {
            $rules = [
                "contractor_request_id" => "required|numeric|exists:contractor_requests,id",
                "checklist_id" => "required|numeric|exists:checklist,id",
                "user_id" => "required|numeric|exists:users,id",
                "checklist_item_detail_id.executive_operation_correctly_contractor.confirm_notconfirm" => "required|in:3,4",
                "checklist_item_detail_id.executive_operation_correctly_contractor.description" => "nullable|string|min:3|max:1024",
                "checklist_item_detail_id.documentation_provided_contractor_consistent_executive.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.documentation_provided_contractor_consistent_executive.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.sufficient_field_view_area.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.sufficient_field_view_area.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.ttcp_scheme_capable_implemented.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.ttcp_scheme_capable_implemented.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.border_middle_enough_space.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.border_middle_enough_space.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.emergency_vehicles_access_executive.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.emergency_vehicles_access_executive.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.necessary_measures_tmp_traffic.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.necessary_measures_tmp_traffic.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.executive_operation_requirement_block_road.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.executive_operation_requirement_block_road.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.pedestrians_motorcyclists_observed_enforcement.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.pedestrians_motorcyclists_observed_enforcement.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.users_considered_executive_operations.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.users_considered_executive_operations.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.intersecting_traffic_flow_vicinity.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.intersecting_traffic_flow_vicinity.description" => 'nullable|string|min:3|max:1024',
                "checklist_item_detail_id.safety_measures_intersecting_traffic_vicinity.confirm_notconfirm" => 'required|in:3,4',
                "checklist_item_detail_id.safety_measures_intersecting_traffic_vicinity.description" => 'nullable|string|min:3|max:1024',
            ];
        } elseif ($checklist_id == 2) {
            $rules = [
                "contractor_request_id" => "required|numeric|exists:contractor_requests,id",
                "checklist_id" => "required|numeric|exists:checklist,id",
                "user_id" => "required|numeric|exists:users,id",
                "checklist_item_detail_id.termination_date" => ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],
                "checklist_item_detail_id.end_time_hours" => ['required', 'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'],
                "checklist_item_detail_id.end_time_day" => "required|numeric|between:1,365",
                "checklist_item_detail_id.traffic_situation" => "required|string|min:3|max:1024",
                "checklist_item_detail_id.weather_conditions" => "required|string|min:3|max:1024",
                "checklist_item_detail_id.time_complete_cleaning" => ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],

            ];
        }
        elseif ($checklist_id == 3){
            $rules = [
                "contractor_request_id" => "required|numeric|exists:contractor_requests,id",
                "checklist_id" => "required|numeric|exists:checklist,id",
                "user_id" => "required|numeric|exists:users,id",
                "checklist_item_detail_id.start_date" => ['required', 'regex:/^[1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9]))\/(30|([1-2][0-9])|(0[1-9]))))$/'],
                "checklist_item_detail_id.start_time_hours" => ['required', 'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'],
                "checklist_item_detail_id.start_time_day" => "required|numeric|between:1,365",
                "checklist_item_detail_id.traffic_situation" => "required|string|min:3|max:1024",
                "checklist_item_detail_id.weather_conditions" => "required|string|min:3|max:1024",

            ];
        }
        elseif ($checklist_id == 4){
            $rules = [
                "checklist_item_detail_id.public_information_arrangements.0.newspaper_advertisements_notices.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.public_information_arrangements.0.providing_information_through_website.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.public_information_arrangements.0.brochures.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.public_information_arrangements.0.variable_message_boards.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.public_information_arrangements.0.comprehensive_radio_television_social.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.public_information_arrangements.0.traffic_radio.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.public_information_arrangements.0.system_141.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.public_information_arrangements.0.contact_information_boards.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.details_notification_messages.0.apology_text_users.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.details_notification_messages.0.time_start_executive_operation.yes_no" => "required|in:1,2",
                "checklist_item_detail_id.details_notification_messages.0.end_time_executive_operation.yes_no" => "required|in:1,2",
                "checklist_item_detail_id.details_notification_messages.0.type_executive_operation.yes_no" => "required|in:1,2",
                "checklist_item_detail_id.details_notification_messages.0.recommended_transit_speed.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.details_notification_messages.0.special_advice_necessary.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.details_notification_messages.0.traffic_changes.yes_no" => "required|in:1,2",
                "checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no" => "required|in:1,2",
                "checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.person" =>
                    "nullable|required_if:checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1|string|min:3|max:1024",
                "checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.position" =>
                    "nullable|required_if:checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1|string|min:3|max:1024",
                "checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.phone_number" =>
                    ['nullable', 'required_if:checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no,==,1','regex:/(09)[0-9]{9}/'],
                "checklist_item_detail_id.details_notification_messages.0.contact_number_emergency_services.yes_no" => "required|in:1,2",
            ];
        }
        elseif($checklist_id == 5){
            $rules = [
                "checklist_item_detail_id.provision_parking_arrangements.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.provision_public_shared_vehicles.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.provision_special_lanes_passenger.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.arrangements_convergence_lines.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.measures_reduce_traffic_congestion.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.using_intelligent_systems.yes_no" => "required|in:7,8",
                "checklist_item_detail_id.creating_overtaking_lanes_heavy_vehicles.created_notcreated" => "required|in:15,16",
                "checklist_item_detail_id.creation_special_lanes_heavy_vehicles.created_notcreated" => "required|in:15,16",
                "checklist_item_detail_id.coordination_adjacent_operational_areas.coordinate_notcoordinate" => "required|in:17,18,19",
                "checklist_item_detail_id.stop_limits.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.control_railway_crossings.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.ramp_control.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.reducing_permitted_speed_using_signs.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.modification_intersections_passages.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.restriction_heavy_vehicles_trucks.done_notdone" => "required|in:5,6",
                "checklist_item_detail_id.circulation_restrictions.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.personnel_safety_training.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.coordination_response_emergency_services.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.surveillance_cctv_cameras.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.patrol.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.law_enforcement_police.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.access_rescue_equipment_emergency.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.presence_someone_emergency_services.used_notused" => "required|in:11,12",
                "checklist_item_detail_id.emergency_vehicle_executive_operation.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.ensuring_safety_pedestrians_users.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.safe_accesses_workshop.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.incident_management_program_workshop.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.detour_options.exist_notexist" => "required|in:19,20",
                "checklist_item_detail_id.necessary_arrangements_maintaining_detour_route.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.ttcp_configuration_considerations_minimize_traffic.provided_notprovided" => "required|in:7,8",
                "checklist_item_detail_id.preparedness_deal_unplanned_events.provided_notprovided" => "required|in:7,8",
            ];
        }
        else {
            return false;
        }
        return $rules;
    }

    public function get_contract_password()
    {
//        $res = Hash::make('password');
//        dd($res);
        $res = DB::table('contract_password')
            ->join('contractor_requests','contractor_requests.id','=','contract_password.contractor_request_id')
            ->join('users','users.id','=','contractor_requests.user_id')
            ->orderBy('contract_password.id', 'desc')
            ->select('contract_password.*','users.email as mobile','contractor_requests.contractor_name')
            ->get();
        return response()->json($res
            , 201);
    }
}
