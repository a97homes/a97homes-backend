<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    protected function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_OK);
    }

    protected function formatResponse(mixed $message, mixed $data, int $code): JsonResponse
    {
        $formattedMessage = is_object($message)
            ? $message
            : ['txt' => is_array($message) ? $message : [$message]];

        return response()->json([
            'message' => $formattedMessage,
            'data' => $data,
        ], $code);
    }
}
