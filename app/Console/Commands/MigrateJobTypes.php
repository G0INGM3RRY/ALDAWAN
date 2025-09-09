<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jobs;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MigrateJobTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:migrate-types {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing job types based on employer profiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('🔄 Starting job type migration...');
        
        // Get all jobs that don't have a job_type set or have default 'formal'
        $jobs = Jobs::with(['user.employerProfile'])
            ->whereNull('job_type')
            ->orWhere('job_type', 'formal')
            ->get();
            
        if ($jobs->isEmpty()) {
            $this->info('✅ No jobs need migration.');
            return;
        }
        
        $this->info("📊 Found {$jobs->count()} jobs to process.");
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($jobs as $job) {
            $employer = $job->user?->employerProfile;
            
            if (!$employer || !$employer->employer_type) {
                $this->warn("⚠️  Job #{$job->id} ({$job->job_title}) - No employer profile or type found. Skipping.");
                $skipped++;
                continue;
            }
            
            $newJobType = $employer->employer_type;
            
            if ($job->job_type === $newJobType) {
                $this->line("✓ Job #{$job->id} ({$job->job_title}) already has correct type: {$newJobType}");
                continue;
            }
            
            if ($dryRun) {
                $this->line("🔍 [DRY RUN] Would update Job #{$job->id} ({$job->job_title}) to '{$newJobType}' type");
            } else {
                $job->update(['job_type' => $newJobType]);
                $this->line("✅ Updated Job #{$job->id} ({$job->job_title}) to '{$newJobType}' type");
            }
            
            $updated++;
        }
        
        if ($dryRun) {
            $this->warn("\n🔍 DRY RUN COMPLETE - No changes were made!");
            $this->info("📊 Would update: {$updated} jobs");
        } else {
            $this->info("\n✅ MIGRATION COMPLETE!");
            $this->info("📊 Updated: {$updated} jobs");
        }
        
        if ($skipped > 0) {
            $this->warn("⚠️  Skipped: {$skipped} jobs (missing employer data)");
        }
        
        $this->info("\n💡 To see what would be changed without making updates, use: php artisan job:migrate-types --dry-run");
    }
}
