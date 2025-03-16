<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Not authorized
        if(!$user || ! Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'code' => 1001, // Login credintial not mathed
                'message' => 'The provided credintial are incorrect!',
            ], 401);
        }


        // Fetch all tokens named 'auth_token' for this user, sorted by creation date (newest last)
        // $tokens = $user->tokens()->where('name', 'auth_token')->orderBy('created_at', 'desc')->get();

        // // Keep only the last 3 tokens, delete the older ones
        // if ($tokens->count() > 3) {
        //     $tokens->slice(3)->each(function ($token) {
        //         $token->delete();
        //     });
        // }

        // Create a new token with a 1-month expiration
        $token = $user->createToken('auth_token', ['*'], now()->addMonth())->plainTextToken;
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
}
