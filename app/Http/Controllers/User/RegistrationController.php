<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\profile\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $passwordValidator = Password::defaults();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'bail|required|unique:profiles',
            'phone_no' => 'bail|required|numeric|digits:10',
            'password' => ['bail', 'required', $passwordValidator->min(8), $passwordValidator->letters(), $passwordValidator->mixedCase(), $passwordValidator->symbols()],
            'cpassword' => 'bail|required|same:password',
            'dob' => 'required',
            'gender' => 'bail|not_in:default'
        ]);

        $userdetails = Profile::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'dob' => $request->dob,
        ]);
        
        $responseData = $userdetails->only(['first_name', 'last_name', 'email', 'phone_no', 'gender', 'dob']);
        $responseData['message'] = 'Registration successfull.';
        return response($responseData, 201);
    }
}
