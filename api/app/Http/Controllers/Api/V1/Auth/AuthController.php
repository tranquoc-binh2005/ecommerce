<?php 
namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\Config\ApiResponseKey;
use App\Enums\Config\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;
use App\Traits\Loggable;
use App\Services\Interfaces\Auth\AuthServiceInterface as AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Illuminate\Http\Request;
use App\Exceptions\SecurityException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller {

    use Loggable;

    private $authService;

    public function __construct(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function authenticate(AuthRequest $request): JsonResponse{
        try {
            $response = $this->authService->login($request);
            return ApiResource::ok($response[ApiResponseKey::DATA], Common::SUCCESS)->withCookie($response[ApiResponseKey::AUTH_COOKIE]);
        } catch (AuthenticationException $e) {
            return ApiResource::message($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function me(): JsonResponse{
        try {
            $auth = $this->authService->me();
            return ApiResource::ok($auth, Common::SUCCESS);
        } catch (UserNotDefinedException $e) {
            return ApiResource::message($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function refresh(Request $request): JsonResponse{
        try {
            $response = $this->authService->refreshAccessToken($request);
            return ApiResource::ok($response[ApiResponseKey::DATA], Common::SUCCESS)->withCookie($response[ApiResponseKey::AUTH_COOKIE]); 
        } catch (ModelNotFoundException $e) {
            return ApiResource::message($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (SecurityException $e) {
            return ApiResource::message($e->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }


    public function logout(): JsonResponse{
        try {
            $this->authService->signout();
            return ApiResource::message(Common::SUCCESS);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }
    

}