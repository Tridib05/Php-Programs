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
        Schema::create('cv_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->index();
            $table->string('title')->nullable(); // e.g., "Full Stack Developer"
            $table->text('bio')->nullable(); // Short professional summary
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('location')->nullable(); // City, Country
            $table->string('website_url')->nullable(); // Personal website
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->longText('about_me')->nullable(); // Detailed about section
            $table->string('profile_photo')->nullable(); // Photo URL/path
            $table->string('cv_file')->nullable(); // PDF file path
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_profiles');
    }
};
