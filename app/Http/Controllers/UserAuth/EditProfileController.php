<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Http\Request;
use App\Models\profile\Profile;
use App\Http\Controllers\Controller;

class EditProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if(count($request->all()) > 4){
            return response(["message" => "No of request fields doen't match.", "status_code" => 404], 404);
        }

        $authUser = auth()->user();
        $user = Profile::where('email', $authUser->email)->first();

        if($request->has('dob') || $request->has('gender') || $request->has('phone_no')){
            if($request->has('dob')){
                $user->dob = $request->dob;
            }
            if($request->has('gender')){
                $user->gender = $request->gender;
            }
            if($request->has('phone_no')){
                $user->phone_no = $request->phone_no;
            }
            $user->save();
        }

        if($request->has('address')){
            $user->update(['address' => $request->address]);
            $user->save();
            $filteredData = $user->only(['first_name', 'last_name', 'email', 'phone_no', 'gender', 'address']);
            $filteredData['message'] = 'Informations are updated successfully.';
            $filteredData['status_code'] = 201;
            return response($filteredData, 201);
        }
        else{
            return response(['message' => 'Information is updated.', 'status_code' => 200], 200);
        }
    }
}
