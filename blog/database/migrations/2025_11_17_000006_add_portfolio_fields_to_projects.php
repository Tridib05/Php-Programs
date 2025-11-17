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
        Schema::table('projects', function (Blueprint $table) {
            // Track submitted portfolio entries
            $table->string('submitted_by')->nullable()->after('impact')->comment('Name of person who submitted (for community entries)');
            $table->boolean('is_approved')->default(true)->after('submitted_by')->comment('Whether this entry is approved for public display');
            $table->enum('submission_type', ['direct', 'community'])->default('direct')->after('is_approved')->comment('direct = created in admin, community = submitted via portfolio form');
            $table->string('submission_email')->nullable()->after('submission_type')->comment('Email of community submission');
            $table->string('submission_website')->nullable()->after('submission_email')->comment('Website URL from community submission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['submitted_by', 'is_approved', 'submission_type', 'submission_email', 'submission_website']);
        });
    }
};
