<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    /**
     * Return a 200 OK JSON response.
     */
    protected function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_OK);
    }

    /**
     * Return a 404 Not Found JSON response.
     */
    protected function notFound(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a 401 Unauthorized JSON response.
     */
    protected function unauthorized(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a 403 Forbidden JSON response.
     */
    protected function forbidden(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a 429 Too Many Requests JSON response.
     */
    protected function tooManyRequests(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Return a 422 Unprocessable Entity JSON response.
     */
    protected function unprocessable(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return a 500 Internal Server Error JSON response.
     */
    protected function serverError(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Return a 405 Method Not Allowed JSON response.
     */
    protected function methodNotAllowed(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Format the JSON response with the specified structure.
     */
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
