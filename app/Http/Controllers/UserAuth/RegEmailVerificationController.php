<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\EmailVerification;
use App\Models\profile\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Auth\SignupEmailVerification;

class RegEmailVerificationController extends Controller
{
    public function send_email(Request $request){
        $user = Profile::where('email', $request->email)->first();
        $email = $user->email;
        // Check if the user in not registered yet
        if(!$user){
            return response(['message' => 'Oops!!! User not found.', 'status_code' => 404], 404);
        }
        
        
        // Check if the user has already verified his/her email 
        if($user->is_verified){
            return response(['message' => 'Email is already verified.', 'is_verified' => true]);
        }
        else{
            $userforEmailVerify = SignupEmailVerification::where('profile_id', $user->id)->first();
            if($userforEmailVerify){
                $token = Str::random(30);
                $otp = rand(10000, 99999);
                $userforEmailVerify->otp = $otp;
                $userforEmailVerify->token = $token;
                $userforEmailVerify->save();
                Mail::to($email)->send(new EmailVerification($user, $userforEmailVerify));
                return response(['message' => 'Email has been resent', 'token' => $token, 'otp' => $otp, 'receiver_email' => $email, 'status_code' => 200], 200);
            }
            else{
                $token = Str::random(30);
                $otp = rand(10000, 99999);
                $userforEmailVerify = SignupEmailVerification::create([
                    'profile_id' => $user->id,
                    'token' => $token,
                    'otp' => $otp
                ]);
                Mail::to($email)->send(new EmailVerification($user, $userforEmailVerify));
                return response(['message' => 'Email has been sent', 'token' => $token, 'otp' => $otp, 'receiver_email' => $email, 'status_code' => 200], 200);
            }
        }
    }

    public function verify_email(Request $request){
        $request->validate([
            'email' => 'required',
            'token' => 'required',
            'otp' => 'required'
        ]);
        // To fetch the registered user
        $user = Profile::where('email', $request->email)->first();
        
        if(!$user){
            return response(['message' => 'User not found', 'status_code' => 404], 404);
        }

        $userforEmailVerify = SignupEmailVerification::where('profile_id', $user->id)->where('token', $request->token)->first();
        
        if($user->is_verified){
            return response(['message' => 'Email is already verified', 'status_code' => 422], 422);
        }
        else{
            if(!$userforEmailVerify){
                return response([
                    'message' => 'Token is invalid',
                    'status_code' => 404
                ], 404);
            }
        }
        

        $current_time = Carbon::now();
        $messageSentTime = $userforEmailVerify->updated_at;
        $timeDifference = $current_time->diffInMinutes($messageSentTime);
        if($timeDifference > 5){
            return response(['message' => 'OTP is expired. Send mail again', 'status_code' => 404], 404);
        }
        else if($request->otp !== $userforEmailVerify->otp){
            return response(['message' => 'OTP is wrong']);
        }

        $userforEmailVerify->delete();
        $user->is_verified = true;
        $user->save();

        return response(['message' => 'Email is verified successfully', 'verified_email' => $request->email, 'status_code' => 200, 'is_verified' => true], 200);
    }
}
