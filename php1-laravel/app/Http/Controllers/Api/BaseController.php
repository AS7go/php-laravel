<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function userCan(string $ability): bool
    {
        return auth()->user()->tokenCan($ability) || auth()->user()->tokenCan('full');
    }

    protected function notAllowedResponse(): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'You are not allowed to this endpoint'
        ], Response::HTTP_FORBIDDEN);
    }
}
