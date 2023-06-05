<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function addplayer(Request $request)
    {
        $validator = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:profiles',
            'phoneno' => 'bail|required|numeric|digits:10',
            'password' => 'bail|required|min:6',
            'cpassword' => 'bail|required|same:password',
            'dob' => 'required',
            'gender' => 'bail|required|not_in:default'
        ]);

        
        $newPlayer = new Profile;
        $newPlayer->firstname = $request->firstname;
        $newPlayer->lastname = $request->lastname;
        $newPlayer->email = $request->email;
        $newPlayer->phoneno = $request->phoneno;
        $newPlayer->password = $request->password;
        $newPlayer->gender = $request->gender;
        $newPlayer->dob = $request->dob;
        $newPlayer->save();
        return response()->json($newPlayer->all(), 200);
    }
}
