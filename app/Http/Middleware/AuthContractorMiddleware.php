<?php

namespace App\Http\Middleware;

use App\Http\Utility\Utility;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthContractorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($request->hasHeader('Authorization'))
            {
                $access_token = $request->header('Authorization');
                $token = substr($access_token,'7',strlen($access_token));
                $user = new Utility();
                $user = $user->decode_jwt_id($token);
                if($user['role_id']==3)
                {
                    $userInfo = User::find($user['user_id']);
                    Auth::login($userInfo);
                    return $next($request);
                }
                else
                {
                    return response()->json([
                        'msg'=>'not allowed'
                    ]) ;
                }
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
