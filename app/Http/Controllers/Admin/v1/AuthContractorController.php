<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Utility\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthContractorController extends Controller
{
    public function login_contractor(Request $request)
    {
        $this->validate($request,[
            'contractor_mobile'=>'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'password'=>'required|size:8',
        ]);
        $passInput = $request->input('password');
        $res = DB::table('users')
            ->join('contractor_requests','users.id','=','contractor_requests.user_id')
            ->where('users.email','=',$request->input('contractor_mobile'))
            ->where('contractor_requests.status','=',2)
            ->where('contractor_requests.password','!=',"0")
            ->select('users.email','users.id as userid','contractor_requests.*')
            ->get();

        if (count($res)>0){
            foreach ($res as $val){
                $passdb = $val->password;
                if (Hash::check($passInput,$passdb)){
                    $util = new Utility();
                    $token = $util->create_jwt($val->userid);
                    return response()->json([
                        'token'=>$token,
                        'contractor_request_id'=>Crypt::encrypt($val->id),
                        'contractor_name'=>$val->contractor_name
                    ]);
                }
                else{
                    return response()->json([
                        'data' => [
                            'message' => 'نام کاربری یا رمز عبور اشتباه است'
                        ],
                    ], 401);
                }
            }
        }
        else{
            return response()->json([
                'data' => [
                    'message' => 'رکوردی یافت نشد'
                ],
            ], 401);
        }
    }
}
