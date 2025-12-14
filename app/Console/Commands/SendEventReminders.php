<?php

namespace App\Console\Commands;

use App\Jobs\SendEventReminderEmailJob;
use App\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event reminder emails to participants for upcoming events.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send event reminders...');

        $today = Carbon::today();

        // Reminders for H-7 (7 days before the event)
        $sevenDaysFromNow = $today->copy()->addDays(7)->endOfDay();
        $this->sendRemindersForDate($today, $sevenDaysFromNow, 'H-7');

        // Reminders for H-1 (1 day before the event)
        $oneDayFromNow = $today->copy()->addDay()->endOfDay();
        $this->sendRemindersForDate($today, $oneDayFromNow, 'H-1');

        // Record the execution time in cache, expiring at the end of the day
        Cache::put('reminder_last_run_timestamp', now(), now()->endOfDay());

        $this->info('Event reminder process completed.');
    }

    /**
     * Helper method to find events and dispatch jobs for a specific date range.
     */
    protected function sendRemindersForDate(Carbon $today, Carbon $targetDate, string $type)
    {
        $events = Event::where('status', 'published')
                       ->whereDate('jadwal', $targetDate->toDateString())
                       ->get();

        if ($events->isEmpty()) {
            $this->info("No events found for {$type} reminder on " . $targetDate->toDateString());
            return;
        }

        foreach ($events as $event) {
            $this->line("Processing {$type} reminder for event: " . $event->title);

            $registrations = $event->registrations()->where('status', 'confirmed')->get();

            if ($registrations->isEmpty()) {
                $this->line("  No confirmed registrations for this event.");
                continue;
            }

            foreach ($registrations as $registration) {
                SendEventReminderEmailJob::dispatch($registration);
                $this->line("  Dispatched {$type} reminder job for user: " . $registration->user->name . " (Event: " . $event->title . ")");
            }
        }
    }
}
