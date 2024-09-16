<?php

namespace App\Http\Utility;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Utility
{
    public function create_jwt($id)
    {
        $key = env('JWT_SECRET');
        $timestamp = Carbon::now()->timestamp;
        $exp = intval($timestamp) + 18000;
        $payload = array(
            "iss" => "https://elyjm.ir",
            "iat" => $timestamp,
            "exp" => $exp,
            'data' => [
                'id' => $id
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
            return $jwt_de->data->id;
        } catch (\Exception $exception) {
            return 'Expired_token';
        }
    }

}
