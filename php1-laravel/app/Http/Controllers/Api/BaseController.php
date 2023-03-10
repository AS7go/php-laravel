<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function checkAbilities(...$abilities)
    {
        foreach ($abilities as $ability) {
            if (auth()->user()->tokenCan($ability)) {
//                dd('work');
                exit;
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'You are not allowed to this endpoint'
        ], Response::HTTP_FORBIDDEN);
    }
}
