<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowContractorRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        if ($user->role_id==6){
            return $next($request);
        }
        else{
            return response()->json([
                'error' => [
                    'message' => 'شما دسترسی به این صفحه را ندارید'
                ],
            ], 403);
        }
    }
}
