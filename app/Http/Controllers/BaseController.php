<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Response\PaginatedResult;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function ok(PaginatedResult $result): JsonResponse
    {
        return response()->json([
            'result' => $result->getResult(),
            'count' => $result->getCount(),
        ]);
    }

    protected function error(string $error): JsonResponse
    {
        return response()->json([
            'error' =>$error,
        ], Response::HTTP_BAD_REQUEST);
    }
}