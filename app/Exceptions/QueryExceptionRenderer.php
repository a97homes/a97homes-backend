<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class QueryExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(QueryException $e): JsonResponse
    {
        Log::error('Query exception rendered to client', ['message' => $e->getMessage()]);

        return $this->unprocessable(__('messages.database_error'));
    }
}
