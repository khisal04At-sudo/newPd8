<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessOpportunityDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opportunities:process-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate opportunity status transitions based on start and end dates.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        $this->info("Processing opportunity dates for: " . $today->toDateString());

        // 1. Published -> Under Implementation (Starts Today)
        $toStart = \App\Models\Opportunity::where('status', \App\Models\Opportunity::STATUS_PUBLISHED)
            ->whereDate('start_date', '<=', $today)
            ->get();

        foreach ($toStart as $opportunity) {
            $opportunity->update(['status' => \App\Models\Opportunity::STATUS_UNDER_IMPLEMENTATION]);
            
            // Bulk update applications to executing
            $opportunity->applications()
                ->where('status', \App\Models\Application::STATUS_ACCEPTED)
                ->update(['status' => \App\Models\Application::STATUS_EXECUTING]);
            
            $this->info("Opportunity ID {$opportunity->id} ('{$opportunity->title}') started.");
        }

        // 2. Under Implementation -> Completed (Ended Yesterday/Before)
        $toComplete = \App\Models\Opportunity::where('status', \App\Models\Opportunity::STATUS_UNDER_IMPLEMENTATION)
            ->whereDate('end_date', '<', $today)
            ->get();

        foreach ($toComplete as $opportunity) {
            $opportunity->update(['status' => \App\Models\Opportunity::STATUS_COMPLETED]);
            
            // Bulk update applications to completed
            $opportunity->applications()
                ->where('status', \App\Models\Application::STATUS_EXECUTING)
                ->update(['status' => \App\Models\Application::STATUS_COMPLETED]);
            
            $this->info("Opportunity ID {$opportunity->id} ('{$opportunity->title}') completed.");
        }

        $this->info('Done processing dates.');
    }
}
