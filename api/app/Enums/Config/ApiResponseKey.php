<?php  
namespace App\Enums\Config;

enum ApiResponseKey: string {

    public const STATUS = 'status';
    public const CODE = 'code';
    public const DATA = 'data';
    public const MESSAGE = 'message';
    public const ERRORS = 'errors';
    public const TIMESTAMP = 'timestamp';
    public const ACCESS_TOKEN = 'accessToken';
    public const EXPIRES_AT = 'expiresAt';
    public const REFRESH_TOKEN = 'refreshToken';
    public const AUTH_COOKIE = 'authCookie';

}