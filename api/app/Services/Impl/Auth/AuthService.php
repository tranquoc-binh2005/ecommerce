<?php
namespace App\Services\Impl\Auth;

use App\Enums\Config\ApiResponseKey;
use App\Enums\Config\Common;
use App\Services\Interfaces\Auth\AuthServiceInterface;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Illuminate\Support\Str;
use App\Repositories\Auth\RefreshTokenRepository;
use App\Repositories\User\UserRepository;
use RuntimeException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\RefreshToken;
use App\Traits\Loggable;
use App\Exceptions\SecurityException;

class AuthService implements AuthServiceInterface {

    use Loggable;

    private $auth;

    private $refreshTokenRepository;
    private $userRepository;

    private const ACCESS_TOKEN_TIME_TO_LIVE = 1;
    private const ACCESS_TOKEN_AFTER_REFRESH_TIME_TO_LIVE = 15;
    private const REFRESH_TOKEN_TIME_TO_LIVE = 7;

    public function __construct(
        RefreshTokenRepository $refreshTokenRepository,
        UserRepository $userRepository
    ){

        /**
         * @var \Tymon\JWTAuth\JWTGuard
         */
        $this->auth = auth(Common::API);
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function login(AuthRequest $request){
        $accessToken = $this->attempLogin($request);
        $refreshToken = $this->createRefreshToken();
        return $this->authResponse($accessToken, $refreshToken);
    }

    private function attempLogin($request): string{
        $credentials = [
            'email' => $request->string('email'),
            'password' => $request->string('password')
        ];

        $this->auth->setTTL(self::ACCESS_TOKEN_TIME_TO_LIVE);
        $this->auth->claims(['guard' => Common::API]);
        if(!$accessToken = $this->auth->attempt($credentials)){
            throw new AuthenticationException(Lang::get('auth.failed'));
        }
        return $accessToken;
    }

    private function createRefreshToken(): string {
        $payload = [
            'refresh_token' => Str::uuid(),
            'expires_at' => now()->addDay(self::REFRESH_TOKEN_TIME_TO_LIVE),
            'user_id' => $this->auth->user()->id
        ];

        if(!$result = $this->refreshTokenRepository->create($payload)){
            throw new RuntimeException(Lang::get('auth.refresh_token_create_failed'));
        }

        return $result->refresh_token;

    }


    private function authResponse(string $accessToken = '', string $refreshToken = ''): array{
        return [
            ApiResponseKey::DATA => [
                ApiResponseKey::ACCESS_TOKEN => $accessToken,
                ApiResponseKey::EXPIRES_AT => $this->auth->factory()->getTTL() * 60,
            ],
            ApiResponseKey::AUTH_COOKIE => Cookie::make(Common::REFRESH_TOKEN_COOKIE_NAME, $refreshToken, self::REFRESH_TOKEN_TIME_TO_LIVE * 24 * 60, '/', null, false, true, false, 'Lax')
        ];
    }

    public function me(){
        try {
            $user = $this->auth->user();
            if(!$user){
                throw new UserNotDefinedException(Lang::get('auth.not_found'));
            }
            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function signout(){
        try {
            $this->auth->invalidate(true);
            $this->auth->logout();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function refreshAccessToken(Request $request): mixed {

        DB::beginTransaction();
        try {
            $refreshToken = $request->cookie(Common::REFRESH_TOKEN_COOKIE_NAME) ?? '';
            if(!$result = $this->refreshTokenRepository->findValidRefreshToken($refreshToken)){
                throw new ModelNotFoundException(Lang::get('auth.not_found'));
            }
            if($this->checkRefreshTokenReuse($result)){
                $this->refreshTokenRepository->revokeAllUserRefreshToken($result->user_id);
                DB::commit();
                throw new SecurityException(Lang::get('auth.refresh_token_used_detected'));
            }

            $result->update(['was_used' => true]);

            $user = $this->userRepository->findById($result->user_id);

            $this->auth->invalidate(true);
            $this->auth->setTTL(self::ACCESS_TOKEN_AFTER_REFRESH_TIME_TO_LIVE);
            $token = $this->auth->login($user);
            $newRefreshToken = $this->createRefreshToken();
            DB::commit();

            return $this->authResponse($token, $newRefreshToken);


        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function checkRefreshTokenReuse(RefreshToken $refreshToken): bool{
        if($refreshToken->was_used || $refreshToken->is_revoked){
            $this->securityLogException($refreshToken);
            return true;
        }
        return false;
    }


}
