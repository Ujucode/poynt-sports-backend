<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function signup(Request $request)
    {
        $validator = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:profiles',
            'phoneno' => 'bail|required|numeric|digits:10',
            'dob' => 'required',
            'gender' => 'bail|required|not_in:default',
            'password' => 'bail|required|min:6',
            'cpassword' => 'bail|required|same:password'

        ]);

        $newPlayer = new Profile;
        $newPlayer->firstname = $request->firstname;
        $newPlayer->lastname = $request->lastname;
        $newPlayer->email = $request->email;
        $newPlayer->phoneno = $request->phoneno;
        $newPlayer->gender = $request->gender;
        $newPlayer->dob = $request->dob;
        $newPlayer->password = Hash::make($request->password);
        $newPlayer->save();
        return response()->json($newPlayer->all(), 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $profile = Profile::where('email', $credentials['email'])->first();
    
        if ($profile && password_verify($credentials['password'], $profile->password)) {
            return response()->json($profile, 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    
}
