<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
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
    protected $description = 'Update status of past events from "published" to "completed"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memperbarui status event...');

        $updatedCount = Event::where('status', 'published')
            ->where('jadwal', '<', now())
            ->update(['status' => 'completed']);

        $message = "Selesai. Sejumlah {$updatedCount} event telah diperbarui menjadi 'completed'.";

        $this->info($message);
        Log::info($message); // Log to storage/logs/laravel.log

        return 0;
    }
}
