<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
       $validator = Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required'
       ]);

       if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
       }

       $user = User::where('email', $request->email)->first();

       if ($user) {
            $isValidPassword = Hash::check($request->password, $user->password);
            if ($isValidPassword) {
                $token = $this->generateToken($user);
                return response()->json([
                    'token' => $token
                ]);
            }
       }

       return response()->json(['message' => 'invalid credentials']);
    }

    private function generateToken($user)
    {
        $jwtKey = env('JWT_KEY');
        $jwtExpired = env('JWT_EXPIRED');

        $now = now()->timestamp;
        $expired = now()->addMinutes($jwtExpired)->timestamp;

        $payload = [    
            'iat' => $now,
            'iss' => 'stream.id',
            'nbf' => $now,
            'exp' => $expired,
            'user' => $user
        ];

        $token = JWT::encode($payload, $jwtKey, 'HS256');

        return $token;
    }
}
