<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubleTypeMatch extends Model
{
    use HasFactory;
    protected $fillable = ['own_role', 'token', 'referee_name', 'profile_id', 'sets_no', 'match_type', 'winning_points', 'first_participant_name', 'first_participant_role', 'first_participant_email', 'second_participant_name', 'second_participant_role', 'second_participant_email', 'third_participant_name', 'third_participant_role', 'third_participant_email', 'fourth_participant_name', 'fourth_participant_role', 'fourth_participant_email', 'group_first_members', 'group_second_members'];
    protected $primaryKey = "match_id";
}
