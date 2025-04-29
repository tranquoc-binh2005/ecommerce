<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Loggable;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Enums\Config\Common;

class Jwt
{

    use Loggable;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        try {
            if(!$request->hasHeader('Authorization')){
                return ApiResource::message(Lang::get('auth.authorization_not_found'));
            }

            $payload = JWTAuth::parseToken()->getPayload();
            
            if($payload->get('guard') !== $guard){
                return ApiResource::message(Lang::get('auth.access_token_invalid'), Response::HTTP_UNAUTHORIZED);
            }

            $user = JWTAuth::parseToken()->authenticate();

        } catch (TokenExpiredException $e) {
            return ApiResource::message(Lang::get('auth.access_token_expired'), Response::HTTP_UNAUTHORIZED);
        }catch (TokenInvalidException $e) {
            return ApiResource::message(Lang::get('auth.access_token_invalid'), Response::HTTP_UNAUTHORIZED);
        }catch (JWTException $e) {
            return ApiResource::message(Common::NETWORK_ERROR, Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            $this->handleLogException($e);
        }
        return $next($request);
    }
}
