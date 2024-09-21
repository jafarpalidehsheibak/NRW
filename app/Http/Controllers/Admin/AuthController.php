<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Utility\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('AuthAdminMiddleware', ['except' => ['login','me']]);
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'password'=>'required',
            'role_id'=>'required|numeric',
        ]);
        $email = $request->input('email');
        $role_id = $request->input('role_id');
        $password = $request->input('password');
        $user = User::query()
            ->where('email',$email)
            ->where('role_id',$role_id)
            ->first();

        if ($user){
            if (Hash::check($password,$user->password)){
                $util = new Utility();
                $token = $util->create_jwt($user->id);
                return response()->json([
                    'token'=>$token,
                    'name'=>$user->name,
                    'username'=>$user->email,
                    'role_id'=>$user->role_id,
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
        else
        {
            return response()->json([
                'data' => [
                    'message' => 'نام کاربری یا رمز عبور اشتباه است'
                ],
            ], 401);
        }
    }

    public function me(Request $request)
    {
        if ($request->hasHeader('Authorization'))
        {
            $access_token = $request->header('Authorization');
            $token = substr($access_token,'7',strlen($access_token));
//            dd($token);
            try {
                $util = new Utility();
                $user_id =  $util->decode_jwt_id($token);
//                dd($user_id);
                if ($user_id=='Expired_token'){
                    return response()->json([
                        'data' => [
                            'msg' => 'داده های ورودی نامعتبر است',
                        ]
                    ]);
                }
                $user = User::find($user_id)->first();
                return response()->json([
                    'name'=>$user->name,
                    'username'=>$user->email,
                    'role_id'=>$user->role_id,
                ]) ;
            }
            catch (\Exception $exception) {
                return response()->json([
                    'data' => [
                        'msg' => 'داده های ورودی نامعتبر است',
                    ]
                ]);
            }
        }
        else
        {
            return response()->json([
                'data' => [
                    'msg' => 'داده های ورودی نامعتبر است',
                ]
            ]);
        }
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 480,
            'name' => auth('api')->user()->name,
            'username' => auth('api')->user()->email,
            'role' => auth('api')->user()->role_id,
        ]);
    }
}
