<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignupEmailVerification extends Model
{
    use HasFactory;
    protected $fillable = ['profile_id', 'otp', 'token'];
}
