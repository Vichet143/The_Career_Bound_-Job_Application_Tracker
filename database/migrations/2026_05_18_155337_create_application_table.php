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
        Schema::create('application', function (Blueprint $table) {
            $table->id("application_id");
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string("company_name");
            $table->string("job_title");
            $table->text("application_date")->nullable();
            $table->text("application_note")->nullable();
            $table->string("template_name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application');
    }
};
