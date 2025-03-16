<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogoutController extends Controller
{


    public function __construct()
    {
        // parent::__construct(); // Call the parent constructor
        // $this->middleware('auth:sanctum');
    }

    public function __invoke(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Delete all expired tokens for this user
        $user->tokens()->where('expires_at', '<', now())->delete();

        // Get the current access token used in this request
        $currentToken = $request->user()->currentAccessToken();

        // Check if the token exists
        if ($currentToken) {
            // Revoke the current token
            $currentToken->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Current token revoked',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Token not found or already revoked',
        ], 404);
    }
}