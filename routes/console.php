<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Tambahkan ini

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan command untuk mengubah status event menjadi 'completed'
Schedule::command('app:update-event-status')->daily()->timezone('Asia/Jakarta');
Schedule::command('app:send-event-reminders')->daily()->timezone('Asia/Jakarta');
