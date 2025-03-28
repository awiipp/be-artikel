<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt($request->only(['name', 'password']))) {
            $user = Auth::user();
            $token = $user->createToken('Token');

            // ini beda sama yg rental car
            return response()->json([
                'message' => 'login success',
                'user' => [
                    'name' => $user->name,
                    'accessToken' => $token->plainTextToken
                ]
            ], 200);
        }

        return response()->json([
            'message' => 'email or password incorrect'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $logout = $user->tokens()->delete();

        if ($logout) {
            return response()->json([
                'message' => 'logout success'
            ], 200);
        }

        return response()->json([
            'message' => 'unauthenticated'
        ], 401);
    }
}
