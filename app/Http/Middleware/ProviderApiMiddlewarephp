<?php

namespace App\Http\Middleware;

use Config;
use Closure;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ProviderApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next)
//    {
//        Config::set('auth.providers.users.model', 'App\Models\Provider');
//
//        try {
//
//            if (!$user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['user_not_found'], 404);
//            } else {
//                \Auth::loginUsingId($user->id);
//            }
//
//        } catch (TokenExpiredException $e) {
//
//            return response()->json(['error' => 'token_expired'], $e->getStatusCode());
//
//        } catch (TokenInvalidException $e) {
//
//            return response()->json(['error' => 'token_invalid'], $e->getStatusCode());
//
//        } catch (JWTException $e) {
//
//            return response()->json(['error' => 'token_absent'.' '.$e->getLine().' '.$e->getMessage()]);
//
//        }
//
//        return $next($request);
//    }
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Auth::setUser($user);
        }
        catch (TokenExpiredException $e) {

            return response()->json([
                'error' => 'Token Expired!',
                'statusCode' => (int)401
            ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => 'Not Authorized!',
                'statusCode' => $e->getFile().' '.$e->getCode()
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Not Authorized!',
                'statusCode' => (int)401
            ], 401);
        }

        return $next($request);
    }
}
