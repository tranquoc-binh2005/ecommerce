<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'refresh_token',
        'expires_at',
        'was_used',
        'is_revoked',
        'user_id',
    ];
}
