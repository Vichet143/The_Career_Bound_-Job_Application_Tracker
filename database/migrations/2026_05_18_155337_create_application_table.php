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
        // FIXED: Renamed 'application' to 'applications'
        Schema::create('applications', function (Blueprint $table) {
            $table->id("application_id");

            // FIXED: Created the column before making it a foreign key
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // FIXED: Added generatecv_id to link to the CV
            $table->unsignedBigInteger('generatecv_id'); 

            $table->string("company_name");
            $table->string("job_title");

            // FIXED: Changed text to date
            $table->date("application_date")->nullable();
            $table->text("application_note")->nullable();
            // $table->string("template_name");

            // FIXED: Added the status column
            $table->enum('status', ['applied', 'interview', 'rejected', 'offer'])->default('applied');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIXED: Renamed to 'applications'
        Schema::dropIfExists('applications');
    }
};
