<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\SingleTypeMatch;

class GamePlayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $token)
    {
        $authUser = auth()->user();
        $playableUser_phaseOne = SingleTypeMatch::where('first_participant_email', $authUser->email)
        ->orWhere('second_participant_email', $authUser->email)->orWhere('token', $token)->first();
        dd($playableUser_phaseOne);
        if($playableUser_phaseOne){
            return response(['message' => 'Ok...now you play game'], 200);
        }
        if(!$playableUser_phaseOne){
            $playableUser_phaseTwo = DoubleTypeMatch::where('first_participant_email', $authUser->email)
            ->orWhere('second_participant_email', $authUser->email)
            ->orWhere('third_participant_email', $authUser->email)->orWhere('fourth_participant_email', $authUser->email)
            ->where('token', $token)->first();

            if(!$playableUser_phaseTwo){
                return response(["message" => "Match not found.", "status_code" => 404], 404);
            }
            else{
                return response(["message" => "Now you can play game.", "status_code" => 200], 200);
            }
        }
    }
}
