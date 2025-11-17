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
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_profile_id')->constrained('cv_profiles')->cascadeOnDelete();
            $table->string('school_name')->index(); // University/College name
            $table->string('degree'); // Bachelor, Master, PhD, etc
            $table->string('field_of_study'); // Major/Specialization
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable(); // Coursework, activities, etc
            $table->decimal('gpa', 3, 2)->nullable();
            $table->json('activities')->nullable(); // Clubs, societies, etc (JSON array)
            $table->string('school_website')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
