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
        Schema::create('generate_cvs', function (Blueprint $table) {
            $table->id('generatecv_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('templates', 'template_id')->onDelete('cascade');
            $table->string('fullname');
            $table->string('job_title')->nullable();
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->text('introduction')->nullable();
            $table->string('project_name')->nullable();
            $table->text('describe_project')->nullable(); 
            $table->text('education')->nullable();
            $table->text('skills')->nullable();
            $table->text('hobbies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_cvs');
    }
};
