<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTExceptionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try 
        {
            if (! $user = JWTAuth::parseToken()->authenticate())
            {
                return [
                    'error' => true,
                    'code'  => 10,
                    'data'  => [
                        'message'   => 'User not found by given token'
                    ]
                ];
            }
        }catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return [
                'error' => true,
                'code'  => 11,
                'data'  => [
                    'message'   => 'Token Expired'
                ]
            ];
        }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return [
                'error' => true,
                'code'  => 12,
                'data'  => [
                    'message'   => 'Invalid Token'
                ]
            ];
        }catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return [
                'error' => true,
                'code'  => 13,
                'data'  => [
                    'message'   => 'Token absent'
                ]
            ];
        }
        return ['error' => false, 'token' => JWTAuth::getToken()];
        
        return $next($request);
    }
}





        
