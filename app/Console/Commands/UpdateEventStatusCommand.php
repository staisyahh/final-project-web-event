<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of events to "completed" if their date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for events to mark as completed...');

        $eventsToUpdate = Event::where('status', 'published')
                               ->where('jadwal', '<', Carbon::now())
                               ->get();

        if ($eventsToUpdate->isEmpty()) {
            $this->info('No events to update.');
            return 0;
        }

        foreach ($eventsToUpdate as $event) {
            $event->status = 'completed';
            $event->save();
            $this->line("Event '{$event->title}' (ID: {$event->id}) has been marked as completed.");
        }

        $this->info("Successfully updated {$eventsToUpdate->count()} events.");
        return 0;
    }
}
