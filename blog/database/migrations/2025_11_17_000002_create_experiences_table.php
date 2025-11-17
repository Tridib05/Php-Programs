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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_profile_id')->constrained('cv_profiles')->cascadeOnDelete();
            $table->string('company_name')->index();
            $table->string('job_title');
            $table->string('employment_type')->nullable(); // Full-time, Part-time, Freelance, etc
            $table->text('description')->nullable(); // Job responsibilities
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null means currently working
            $table->boolean('is_current')->default(false);
            $table->string('company_website')->nullable();
            $table->json('key_achievements')->nullable(); // JSON array of achievements
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
