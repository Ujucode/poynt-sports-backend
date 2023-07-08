<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Http\Request;
use App\Models\profile\Profile;
use App\Http\Controllers\Controller;

class SelectPlayerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($query)
    {
        $players = Profile::where('first_name', 'LIKE', '%' . $query . '%')->where('is_verified', true)->limit(10)->get();
        $response = [];
        foreach($players as $player){
            array_push($response, ['name' => $player->first_name. " ". $player->last_name, "email" => $player->email]);
        }
        return response($response, 200);
    }
}
