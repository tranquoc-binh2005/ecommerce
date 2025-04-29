<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Enums\Config\ApiResponseKey;

class ApiResource extends JsonResource
{

    private const TIMESTAMP_FORMAT = 'Y-m-d\TH:i:s.v\Z';

    public static function ok(mixed $data = null, string $message = '', int $httpStatus = Response::HTTP_OK): JsonResponse{
        return response()->json([
            ApiResponseKey::STATUS => true,
            ApiResponseKey::CODE => $httpStatus,
            ApiResponseKey::DATA => $data,
            ApiResponseKey::MESSAGE => $message,
            ApiResponseKey::TIMESTAMP => now()->format(self::TIMESTAMP_FORMAT)
        ], $httpStatus);
    }

    public static function error(mixed $errors = null, string $message = '', int $httpStatus = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse{
        return response()->json([
            ApiResponseKey::STATUS => false,
            ApiResponseKey::CODE => $httpStatus,
            ApiResponseKey::ERRORS => $errors,
            ApiResponseKey::MESSAGE => $message,
            ApiResponseKey::TIMESTAMP => now()->format(self::TIMESTAMP_FORMAT)
        ], $httpStatus);
    }

    public static function message(string $message = '', int $httpStatus = Response::HTTP_OK): JsonResponse{
        return response()->json([
            ApiResponseKey::STATUS => $httpStatus === Response::HTTP_OK,
            ApiResponseKey::MESSAGE => $message,
            ApiResponseKey::TIMESTAMP => now()->format(self::TIMESTAMP_FORMAT)
        ], $httpStatus);
    }
}
