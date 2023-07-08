<?php

namespace App\Http\Controllers\UserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $authUser = auth()->user();
        $authUser->tokens()->delete();
        return response(['message' => 'Logout successfull.', 'status_code' => 200], 200);
    }
}
