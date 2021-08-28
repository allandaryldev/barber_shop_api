<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function login (AuthRequest $request)
    {
        $credentials = $request->validated();

        if (!$user = Auth::guard('users')->attempt($credentials)) {

            return response()->json(['message' => 'Unauthorized.'], 401);
            
        }

        $user = auth()->guard('users')->user();

        // check for status here
        if ($user->status == 'deactivated') {
            
            return response()->json([
                "message" => "Your account deactivated please contact your admin."],
                401
            );

        }

        // create token here
        $user = auth()->guard('users')->user()->createToken('userToken');

        return new AuthResource($user);

    }
    public function logout (Request $request)
    {   
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out.'], 204);
    }
}
