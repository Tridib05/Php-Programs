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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_profile_id')->constrained('cv_profiles')->cascadeOnDelete();
            $table->string('skill_name')->index();
            $table->string('category')->nullable(); // Programming, Design, Tools, Languages, etc
            $table->integer('proficiency')->default(50); // 1-100 for progress bar
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('icon_class')->nullable(); // For Font Awesome or other icons
            $table->json('endorsements')->nullable(); // JSON array of endorsers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
