<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('single_type_matches', function (Blueprint $table) {
            $table->bigIncrements('match_id');
            $table->string('own_role');
            $table->string('referee_name');
            $table->string('referee_email')->nullable();
            $table->string('first_participant_name')->nullable();
            $table->string('first_participant_role')->nullable();
            $table->string('first_participant_email')->nullable();
            $table->string('second_participant_name');
            $table->string('second_participant_role');
            $table->string('second_participant_email');
            $table->string('match_type');
            $table->integer('sets_no');
            $table->string('winning_points');
            $table->string('token');
            $table->unsignedBigInteger('profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('single_type_matches');
    }
};
