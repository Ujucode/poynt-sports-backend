<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleTypeMatch extends Model
{
    use HasFactory;
    protected $fillable = ['own_role', 'token', 'referee_name', 'first_participant_email', 'second_participant_email', 'profile_id', 'sets_no', 'match_type', 'winning_points', 'first_participant_name', 'first_participant_role', 'second_participant_name', 'second_participant_role'];
    protected $primaryKey = "match_id";
}
