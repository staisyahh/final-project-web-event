<?php

use App\Livewire\Auth\Login;
use App\Livewire\LandingPage;
use App\Livewire\Auth\Register;
use App\Livewire\Member\TiketSaya;
use App\Livewire\Member\Bookmarks;
use App\Livewire\Public\EventDetail;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Admin\Events\Index as EventIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Absensi\Index as AbsensiIndex;
use App\Livewire\Admin\Speakers\Index as SpeakerIndex;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Registrations\Index as RegistrationIndex;
use App\Livewire\Admin\Confirmations\Index as ConfirmationIndex;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

Route::get('/', LandingPage::class)->name('home');
Route::get('/events/{event:slug}', EventDetail::class)->name('event.detail');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Switchboard
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::user()->role === 'peserta') {
            return redirect()->route('home');
        }
        // Fallback for any other roles
        return redirect('/');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/events', EventIndex::class)->name('events.index');
        Route::get('/categories', CategoryIndex::class)->name('categories.index');
        // Speakers Routes
        Route::get('/speakers', SpeakerIndex::class)->name('speakers.index');
        // Registrations Route
        Route::get('/registrations', RegistrationIndex::class)->name('registrations.index');
        // Absensi / Check-in Route
        Route::get('/check-in', AbsensiIndex::class)->name('check-in.index');
        // Users Route
        Route::get('/users', UserIndex::class)->name('users.index');
        // Reviews Route
        Route::get('/reviews', \App\Livewire\Admin\Reviews\Index::class)->name('reviews.index');
        // Confirmations Route
        Route::get('/confirmations', ConfirmationIndex::class)->name('confirmations.index');
        // Reminders Route
        Route::get('/reminders', \App\Livewire\Admin\Reminders\Index::class)->name('reminders.index');
    });

    // Member Routes
    Route::middleware(['auth', 'role:peserta'])->prefix('member')->name('member.')->group(function () {
        Route::get('/tiket', TiketSaya::class)->name('tiket');
        Route::get('/bookmarks', Bookmarks::class)->name('bookmarks');
        Route::get('/tiket/{eTicket}/download', [TiketSaya::class, 'downloadTicket'])->name('ticket.download');
    });
});
