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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('current_location');
            $table->string('education');
            $table->string('career_level');
            $table->string('experience');
            $table->string('position');
            $table->decimal('salary_expectation');
            $table->string('commitment_level');
            $table->string('visa_status');
            $table->string('record_video');
            $table->text('drop_note')->nullable();
            $table->string('cv_path')->nullable();
            $table->enum('status',['new','review','interview','offer','rejected'])->default('new');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->string('application_number')->unique();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
