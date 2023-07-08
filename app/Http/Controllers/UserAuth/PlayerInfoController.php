<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlayerInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $authUser = Auth::user();
        $responseData = $authUser->only(['first_name', 'last_name', 'email', 'phone_no', 'gender']);
        return response($responseData, 200);
    }
}
