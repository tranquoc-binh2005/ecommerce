<?php 
namespace App\Repositories\Auth;
use App\Repositories\BaseRepository;
use App\Models\RefreshToken;

class RefreshTokenRepository extends  BaseRepository{

    protected $model;

    public function __construct(
        RefreshToken $model
    )
    {
        $this->model = $model;    
        parent::__construct($model);
    }

    public function findValidRefreshToken(string $refreshToken = ''): RefreshToken{
        return $this->model
                    ->where('refresh_token', $refreshToken)
                    ->whereDate('expires_at', '>', now())
                    ->where('is_revoked', false)->first();
    }

    public function revokeAllUserRefreshToken(int $userId = 0){
        return $this->model->where('user_id', $userId)
                            ->where('is_revoked', false)
                            ->update(['is_revoked' => true]);
    }

}