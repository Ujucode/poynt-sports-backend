<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\MatchInvitationMail;
use App\Http\Controllers\Controller;
use App\Models\Auth\DoubleTypeMatch;
use App\Models\Auth\SingleTypeMatch;
use Illuminate\Support\Facades\Mail;

class MatchController extends Controller
{
    public function create_match(Request $request){
        
        if($request->match_type == "single"){
            if($request->own_role == "referee" && count($request->all()) !== 10){
                return response(['message' => 'No of request fields are not matching.', 'status_code' => 404], 404);
            }
            else if($request->own_role == "participant" && count($request->all()) !== 9){
                return response(['message' => 'No of request fields are not matching.', 'status_code' => 404], 404);
            }
        }

        if($request->match_type == "double"){
            if($request->own_role == "referee" && count($request->all()) !== 18){
                return response(['message' => 'No of request fields are not matching.', 'status_code' => 404], 404);
            }
            else if($request->own_role == "participant" && count($request->all()) !== 17){
                return response(['message' => 'No of request fields are not matching.', 'status_code' => 404], 404);
            }
        }
        
        // General all required fields for create match are validated first

        $request->validate([
            "own_role" => "bail|required|in:referee,participant",
            "sets_no" => "bail|required|not_in:default",
            "match_type" => "bail|required|in:single,double,mix-double",
            "winning_points" => "bail|required|not_in:default",
        ]);
        
        // If the match is in single mode then - if own role is referee then the other two player's role must be participant
        if($request->match_type == "single"){
            $request->validate([
                "second_participant_name" => "bail|required",
                "second_participant_role" => "bail|required|in:participant",
                "second_participant_email" => "bail|required"
            ]);
            if($request->own_role == "referee"){
                $request->validate([
                    "first_participant_name" => "bail|required",
                    "first_participant_role" => "bail|required|in:participant",
                    "first_participant_email" => "bail|required",
                ]);
            }
            else if($request->own_role == "participant"){
                $request->validate([
                    "referee_name" => "bail|required",
                    "referee_email" => "bail|required",
                ]);
            }
        }

        if($request->match_type == "single"){
            if($request->own_role == "referee"){
                $first_participant_email = $request->first_participant_email;
                $second_participant_email = $request->second_participant_email;
                $token = Str::random(60);

                SingleTypeMatch::create([
                    'own_role' => $request->own_role,
                    'referee_name' => auth()->user()->first_name ." " . auth()->user()->last_name,
                    'first_participant_name' => $request->first_participant_name,
                    'first_participant_role' => $request->first_participant_role,
                    'first_participant_email' => $first_participant_email,
                    'second_participant_name' => $request->second_participant_name,
                    'second_participant_role' => $request->second_participant_role,
                    'second_participant_email' => $second_participant_email,
                    'match_type' => $request->match_type,
                    'sets_no' => $request->sets_no,
                    'winning_points' => $request->winning_points,
                    'token' => $token,
                    'profile_id' => auth()->user()->id,
                ]);
                Mail::to($first_participant_email)->send(new MatchInvitationMail($request->first_participant_name, $request->first_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($second_participant_email)->send(new MatchInvitationMail($request->second_participant_name, $request->second_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));

                return response(["message" => 'Emails are sent successfully.',"receivers" => [$first_participant_email, $second_participant_email], "token" => $token, "status_code" => 200], 200);
            }

            else if($request->own_role == "participant"){
                $second_participant_email = $request->second_participant_email;
                $referee_email = $request->referee_email;
                $referee_role = "referee";
                $token = Str::random(60);

                SingleTypeMatch::create([
                    'own_role' => $request->own_role,
                    'referee_name' => $request->referee_name,
                    'referee_email' => $referee_email,
                    'second_participant_name' => $request->second_participant_name,
                    'second_participant_role' => $request->second_participant_role,
                    'second_participant_email' => $second_participant_email,
                    'match_type' => $request->match_type,
                    'sets_no' => $request->sets_no,
                    'winning_points' => $request->winning_points,
                    'token' => $token,
                    'profile_id' => auth()->user()->id
                ]);
                Mail::to($referee_email)->send(new MatchInvitationMail($request->referee_name, $referee_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($second_participant_email)->send(new MatchInvitationMail($request->second_participant_name, $request->second_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                return response(["message" => "Email are sent successfully", "receivers" => [$second_participant_email, $referee_email], "token" => $token, "status_code" => 200], 200);
            }
        }
        

        // If the match is double
        if($request->match_type == "double"){
            $request->validate([
                "group_first_members" => "bail|required",
                "group_second_members" => "bail|required",
                "second_participant_name" => "bail|required",
                "second_participant_email" => "bail|required",
                "second_participant_role" => "bail|required|in:participant",
                "third_participant_name" => "bail|required",
                "third_participant_email" => "bail|required",
                "third_participant_role" => "bail|required|in:participant",
                "fourth_participant_name" => "bail|required",
                "fourth_participant_email" => "bail|required",
                "fourth_participant_role" => "bail|required|in:participant",
            ]);
            if($request->own_role == "referee"){
                $request->validate([
                    "first_participant_name" => "bail|required",
                    "first_participant_email" => "bail|required",
                    "first_participant_role" => "bail|required|in:participant",
                ]);
            }
            else if($request->own_role == "participant"){
                $request->validate([
                    "referee_name" => "bail|required",
                    "referee_email" => "bail|required",
                ]);
            }
        }

        if($request->match_type == "double"){
            if($request->own_role == "referee"){
                $first_participant_email = $request->first_participant_email;
                $second_participant_email = $request->second_participant_email;
                $third_participant_email = $request->third_participant_email;
                $fourth_participant_email = $request->fourth_participant_email;
                $token = Str::random(60);

                DoubleTypeMatch::create([
                    'own_role' => $request->own_role,
                    'referee_name' => auth()->user()->first_name ." " . auth()->user()->last_name,
                    'first_participant_name' => $request->first_participant_name,
                    'first_participant_email' => $request->first_participant_email,
                    'first_participant_role' => $request->first_participant_role,
                    'second_participant_name' => $request->second_participant_name,
                    'second_participant_email' => $request->second_participant_email,
                    'second_participant_role' => $request->second_participant_role,
                    'third_participant_name' => $request->third_participant_name,
                    'third_participant_email' => $request->third_participant_email,
                    'third_participant_role' => $request->third_participant_role,
                    'fourth_participant_name' => $request->fourth_participant_name,
                    'fourth_participant_email' => $request->fourth_participant_email,
                    'fourth_participant_role' => $request->fourth_participant_role,
                    'group_first_members' => json_encode($request->group_first_members),
                    'group_second_members' => json_encode($request->group_second_members),
                    'match_type' => $request->match_type,
                    'sets_no' => $request->sets_no,
                    'winning_points' => $request->winning_points,
                    'token' => $token,
                    'profile_id' => auth()->user()->id,
                ]);
                Mail::to($first_participant_email)->send(new MatchInvitationMail($request->first_participant_name, $request->first_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($second_participant_email)->send(new MatchInvitationMail($request->second_participant_name, $request->second_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($third_participant_email)->send(new MatchInvitationMail($request->third_participant_name, $request->third_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($fourth_participant_email)->send(new MatchInvitationMail($request->fourth_participant_name, $request->fourth_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                return response(["message" => "Emails are sent successfully", "receivers" => [$first_participant_email, $second_participant_email, $third_participant_email, $fourth_participant_email],"token" => $token, "status_code" => 200], 200);
            }

            else if($request->own_role == "participant"){
                $referee_email = $request->referee_email;
                $second_participant_email = $request->second_participant_email;
                $third_participant_email = $request->third_participant_email;
                $fourth_participant_email = $request->fourth_participant_email;
                $token = Str::random(60);

                DoubleTypeMatch::create([
                    "own_role" => $request->own_role,
                    'referee_name' => $request->referee_name,
                    'referee_email' => $request->referee_email,
                    'second_participant_name' => $request->second_participant_name,
                    'second_participant_email' => $request->second_participant_email,
                    'second_participant_role' => $request->second_participant_role,
                    'third_participant_name' => $request->third_participant_name,
                    'third_participant_email' => $request->third_participant_email,
                    'third_participant_role' => $request->third_participant_role,
                    'fourth_participant_name' => $request->fourth_participant_name,
                    'fourth_participant_email' => $request->fourth_participant_email,
                    'fourth_participant_role' => $request->fourth_participant_role,
                    'group_first_members' => json_encode($request->group_first_members),
                    'group_second_members' => json_encode($request->group_second_members),
                    'match_type' => $request->match_type,
                    'sets_no' => $request->sets_no,
                    'winning_points' => $request->winning_points,
                    'token' => $token,
                    'profile_id' => auth()->user()->id,
                ]);
                Mail::to($referee_email)->send(new MatchInvitationMail($request->referee_name, "referee", auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($second_participant_email)->send(new MatchInvitationMail($request->second_participant_name, $request->second_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($third_participant_email)->send(new MatchInvitationMail($request->third_participant_name, $request->third_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                Mail::to($fourth_participant_email)->send(new MatchInvitationMail($request->fourth_participant_name, $request->fourth_participant_role, auth()->user()->first_name ." " . auth()->user()->last_name, $token));
                return response(["message" => "Emails are sent successfully.", "receivers" => [$referee_email, $second_participant_email, $third_participant_email, $fourth_participant_email], "token" => $token, "status_code" => 200], 200);
            }
        }
    }
}