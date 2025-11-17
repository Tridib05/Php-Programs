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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_profile_id')->constrained('cv_profiles')->cascadeOnDelete();
            $table->string('project_name')->index();
            $table->text('description');
            $table->text('detailed_description')->nullable();
            $table->string('project_url')->nullable(); // Live project URL
            $table->string('github_url')->nullable(); // GitHub repository
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->json('technologies')->nullable(); // JSON array of tech stack
            $table->json('images')->nullable(); // JSON array of project images
            $table->string('featured_image')->nullable(); // Featured image path
            $table->text('impact')->nullable(); // Project impact/results
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
