<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\CVProfile;

class ImportPortfolioEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:import {--cv-id=1 : The CV profile ID to attach projects to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import portfolio entries from storage/app/portfolio.json into projects table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cvId = $this->option('cv-id');
        
        // Verify CV exists
        $cv = CVProfile::find($cvId);
        if (!$cv) {
            $this->error("❌ CVProfile with ID {$cvId} not found.");
            return 1;
        }

        // Load JSON entries
        if (!Storage::exists('portfolio.json')) {
            $this->warn("⚠️  No portfolio.json file found in storage/app/");
            return 0;
        }

        $json = Storage::get('portfolio.json');
        $entries = json_decode($json, true) ?: [];

        if (empty($entries)) {
            $this->info("ℹ️  No entries to import.");
            return 0;
        }

        $this->info("📂 Found " . count($entries) . " portfolio entries. Importing...\n");

        $imported = 0;
        $skipped = 0;

        foreach ($entries as $entry) {
            try {
                // Check if already imported (by name and submitted_by)
                $existing = Project::where('cv_profile_id', $cvId)
                    ->where('project_name', $entry['name'] ?? 'Unnamed')
                    ->where('submission_type', 'community')
                    ->where('submitted_by', $entry['name'] ?? 'Unknown')
                    ->first();

                if ($existing) {
                    $this->line("⏭️  Skipped: {$entry['name']} (already imported)");
                    $skipped++;
                    continue;
                }

                // Create project from portfolio entry
                Project::create([
                    'cv_profile_id' => $cvId,
                    'project_name' => $entry['name'] ?? 'Unnamed Project',
                    'description' => $entry['title'] ?? 'Community submission',
                    'detailed_description' => $entry['bio'] ?? '',
                    'project_url' => $entry['website'] ?? null,
                    'github_url' => null,
                    'start_date' => $entry['created_at'] ?? now(),
                    'end_date' => null,
                    'is_current' => false,
                    'technologies' => [],
                    'images' => [],
                    'featured_image' => null,
                    'impact' => null,
                    'submitted_by' => $entry['name'] ?? 'Unknown',
                    'is_approved' => true,
                    'submission_type' => 'community',
                    'submission_email' => $entry['email'] ?? null,
                    'submission_website' => $entry['website'] ?? null,
                ]);

                $this->line("✅ Imported: {$entry['name']}");
                $imported++;

            } catch (\Exception $e) {
                $this->error("❌ Error importing {$entry['name']}: " . $e->getMessage());
            }
        }

        $this->info("\n" . str_repeat('=', 60));
        $this->info("Import Complete!");
        $this->info("✅ Imported: {$imported}");
        $this->info("⏭️  Skipped: {$skipped}");
        $this->info("Total: " . ($imported + $skipped) . " entries processed");
        $this->info(str_repeat('=', 60));

        return 0;
    }
}
