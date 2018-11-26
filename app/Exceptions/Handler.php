<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        /*\Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,*/
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request,AuthenticationException $ex)
    {
        
        if($ex instanceof TokenExpiredException){
            if($request->wantsJson()){
                return response()->json('Token Expired',400);
            }
        }
            

        if($ex instanceof TokenInvalidException){
            if($request->wantsJson()){
                return response()->json('Token not valid',400);
            }
        }
            

        if($ex instanceof JWTException){
            if($request->wantsJson()){
                return response()->json('Token Error!',400);
            }
        }
            

        if($ex instanceof InvalidClaimException){
            if($request->wantsJson()){
                return response()->json('Token Old!',400);
            }
        }
            

        if($ex instanceof TokenBlacklistedException){
            if($request->wantsJson()){
                return response()->json('Token Blacklisted!',400);
            }
        }
            
        
        if($request->wantsJson()){
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized.',
                'data' => []
            ],401);
        } 
        
        return redirect()->guest(route('admin-login'));
    }
}
