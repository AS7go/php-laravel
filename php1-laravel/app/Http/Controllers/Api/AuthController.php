<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(AuthRequest $request)
    {
//        dd($request->validated());
        if (!auth()->attempt($request->validated())){
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
        }

        $permissions = auth()->user()->hasAnyRole('admin', 'editor') ? ['full'] : ['read'];

//        $token = auth()->user()->tokens()->get();
//        $token = auth()->user()->tokens()->first()?->token;
//        dd(auth()->user()->tokens()->first()->token);
//        $token = auth()->user()->tokens()->first()?->plainTextToken;
//        dd($token);
//        dd(auth()->user());
        return response()->json([
            'status' => 'success',
            'data' => [
//                'token' =>$token ?? auth()->user()->createToken($request->device_name ?? 'api', $permissions)->plainTextToken
                'token' => auth()->user()->createToken($request->device_name ?? 'api', $permissions)->plainTextToken
            ]
        ]);
    }
}
