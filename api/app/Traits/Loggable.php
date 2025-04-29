<?php  
namespace App\Traits;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;
use App\Enums\Config\Common;
use App\Models\RefreshToken;
use Illuminate\Support\Facades\Lang;

trait Loggable {

    protected function handleLogException(\Exception $e, string $message = Common::ERROR_MESSAGE){
        Log::error("Error message: ", [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        return ApiResource::message(Common::NETWORK_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function securityLogException(RefreshToken $refreshToken){
        Log::warning(Lang::get('auth.refresh_token_used_detected'), [
            'refreshToken' => $refreshToken->refresh_token,
            'user_id' => $refreshToken->user_id,
            'ip' => request()->ip()
        ]);
    }

}