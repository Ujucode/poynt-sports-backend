<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\UserAuth\MatchController;
use App\Http\Controllers\UserAuth\LogoutController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\UserAuth\GamePlayController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\UserAuth\PlayerInfoController;
use App\Http\Controllers\UserAuth\EditProfileController;
use App\Http\Controllers\UserAuth\SelectPlayerController;
use App\Http\Controllers\UserAuth\RegEmailVerificationController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('player')->group(function () {
    Route::post('signup', RegistrationController::class);
    Route::get('login', LoginController::class);   
    Route::post('verify/email', [RegEmailVerificationController::class, 'verify_email']);
    Route::post('send-signup-email', [RegEmailVerificationController::class, 'send_email']);
    Route::post('reset-password', ResetPasswordController::class);
});

// Sanctum authentication
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('player')->group(function () {
        Route::post('logout', LogoutController::class);
        Route::post('edit-profile', EditProfileController::class);

        // to know all info of logged in user
        Route::get('info', PlayerInfoController::class);
        //to create match
        Route::post('create-match', [MatchController::class, 'create_match']);

        // to select users by searching for gameplay
        Route::get('select/{query}', SelectPlayerController::class);

        // for gameplay, to also check the link is valid or not
        Route::get('gameplay/{token}', GamePlayController::class);
    });
});