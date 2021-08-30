<?php

namespace App\Http\Controllers\Users;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUserPasswordFormRequest;
use App\Models\User;

class ChangeUserPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ChangeUserPasswordFormRequest $request, User $user)
    {
        $input = $request->validated();
        
        $user->update([
            'password' => Hash::make($input['password'])
        ]);

        return response()->json(['message' => 'Successfully changed password.']);
    }
}
