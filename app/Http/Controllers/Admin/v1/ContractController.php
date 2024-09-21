<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SafetyConsultantCollection;
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
        $this->middleware(['AuthContractorMiddleware']);
    }
    public function show_all_safety_consultant()
    {
        $safety_consultant =DB::table('users')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('experts', 'experts.id', '=', 'profiles.expert_id')
            ->select('users.*', 'profiles.phone_number','profiles.address', 'roles.role_name', 'experts.name_expert','experts.id as expertId')
            ->where('users.role_id', '=', 4)
            ->where('users.status', '=', 1)
            ->orderBy('id','desc')
            ->paginate(10);
        return response()->json(
            new SafetyConsultantCollection($safety_consultant)
            , 200);
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
        elseif ($checklist_id == 5)
        {
            $rules = [
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

                'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave' => 'required|in:9,10',
                'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.jadde_enherafi_type_color' => 'nullable|string|required_if:signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave,9',
                'signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.jadde_enherafi_company_color' => 'nullable|string|required_if:signs_equipment_operation_area.jadde_enherafi.jadde_enherafi_value.have_nothave,9',

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
