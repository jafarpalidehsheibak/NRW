<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractorRequestResource;
use App\Http\Utility\Utility;
use App\Models\ContractorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContractorRequestCycleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['AuthContractorMiddleware']);
    }
    public function show_contract_request(Request $request)
    {
        $access_token = $request->header('Authorization');
        $token = substr($access_token,'7',strlen($access_token));
        $user = new Utility();
        $user = $user->decode_jwt_id($token);
        $contract_request_id = $user['contractor_requests_id'];
        try {
            $res = DB::table('contractor_requests')
                ->join('provinces', 'contractor_requests.province_id', '=', 'provinces.id')
                ->join('cities', 'contractor_requests.city_id', '=', 'cities.id')
                ->join('experts', 'contractor_requests.expert_id', '=', 'experts.id')
                ->join('status_request', 'contractor_requests.status', '=', 'status_request.id')
                ->join('users', 'users.id', '=', 'contractor_requests.user_id')
                ->where('contractor_requests.id', '=', $contract_request_id)
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

    public function update_safety_consultant(Request $request)
    {
        $this->validate($request,[
            'safety_consultant_id'=>'required|exists:users,id',
        ]);
        $access_token = $request->header('Authorization');
        $token = substr($access_token,'7',strlen($access_token));
        $user = new Utility();
        $user = $user->decode_jwt_id($token);
        $contract_request_id = $user['contractor_requests_id'];
        try {
            $ContractorRequestItem = ContractorRequest::find($contract_request_id);
            $updated_ContractorRequest = $ContractorRequestItem->update([
                'safety_consultant_id'=>$request->input('safety_consultant_id'),
                'status'=>4
            ]);
            if ($updated_ContractorRequest) {
                return response()->json([
                    'data' => [
                        'msg' => 'مشاور ایمنی با موفقیت انتخاب شد',
                    ]
                ]);
            }
        }
        catch (\Exception $exception) {
            return response()->json([
                'data' => [
                    'msg' => 'داده های ورودی نامعتبر است',
                ]
            ]);
        }


    }

}
