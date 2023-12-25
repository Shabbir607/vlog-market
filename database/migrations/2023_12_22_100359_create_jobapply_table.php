<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $fillable=['user_id','application_id','status','user_name'];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobapply', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->string('user_name');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->enum('status', ['new', 'review', 'interview', 'offer', 'rejected'])->default('new');
            $table->foreign('application_id')->references('id')->on('job_applications')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobapply');
    }
};
