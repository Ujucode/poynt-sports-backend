<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\profile\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\SignupEmailVerification;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'bail|required|min:6'
        ]);

        $user = Profile::where('email', $request->email)->first();
        if(!$user){
            return response(['message' => 'User not found', 'status_code' => 404], 404);
        }
        $checkUser = SignupEmailVerification::where('profile_id', $user->id)->first();

        if($user && Hash::check($request->password, $user->password)){

            // Checking if a user's email is verified or not.
            if(!$user->is_verified){
                return response(['message' => 'Your email hasn\'t verified.', 'is_verified' => false, 'status_code' => 404],404);
            }
            $token = $user->createToken($request->email)->plainTextToken;

            // After successfull login which things is required, all those task will be done here.
            
        
            return response(['token' => $token, 'message' => 'Login Successfull.', 'status_code' => 200], 200);
        }
        else{
            return response(['message' => 'Email or password is incorrect.', 'status_code' => 404], 404);
        }
    }
}
