<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\profile\Profile;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = $request->validate([
            'email' => 'bail|required',
            'password' => 'bail|required|min:6',
            'cpassword' => 'bail|required|min:6|same:password'
        ]);

        $user = Profile::where('email', $request->email)->first();
        if(!$user){
            return response([
                'message' => 'User not found.',
                'status_code' =>  404
            ], 404);
        }
        $user->password = $request->password;
        $user->save();
        return response([
            'message' => 'Password reset successfully.',
            'new_password' => $user->password,
            'status_code' => 201
        ],201);
    }
}
