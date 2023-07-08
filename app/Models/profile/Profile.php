<?php

namespace App\Models\profile;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable=['first_name', 'last_name', 'email', 'phone_no', 'password', 'dob', 'gender', 'address', 'is_verified'];
}
