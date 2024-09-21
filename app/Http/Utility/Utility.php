<?php

namespace App\Http\Utility;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Crypt;

class Utility
{
    public function create_jwt($id,$contractor_requests_id = "")
    {
        $key = env('JWT_SECRET');
        $timestamp = Carbon::now()->timestamp;
        $exp = intval($timestamp) + 18000;
        $payload = array(
            "iss" => "https://elyjm.ir",
            "iat" => $timestamp,
            "exp" => $exp,
            'data' => [
                'id' => Crypt::encrypt($id),
                'contractor_requests_id' => Crypt::encrypt($contractor_requests_id),
            ]
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function decode_jwt_id($jwt)
    {
        try {
            $key = env('JWT_SECRET');
            $jwt_de = JWT::decode($jwt, new Key($key, 'HS256'));
            $id_user_en =$jwt_de->data->id;
            $contractor_requests_id_en =$jwt_de->data->contractor_requests_id;
            $id_user_en =Crypt::decrypt($id_user_en);
            $user = User::find($id_user_en);
            return [
                'user_id'=>$user->id,
                'role_id'=>$user->role_id,
                'contractor_requests_id'=>Crypt::decrypt($contractor_requests_id_en),
            ];
        } catch (\Exception $exception) {
            return 'Expired_token';
        }
    }

}
