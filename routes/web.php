<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // session.store endpoint is only for demo purposes
    Route::post('/session', [DashboardController::class, 'store'])->name('session.store');

    // session.show endpoint is only for demo purposes
    Route::get('/session/{session}', [DashboardController::class, 'show'])->name('session.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/timezone', [ProfileController::class, 'updateTimezone'])->name('profile.update.timezone');
    Route::patch('/profile/mentor/status', [ProfileController::class, 'updateMentorStatus'])->name('profile.update.mentor.status');
    Route::get('/profile/mentor/information', [ProfileController::class, 'showMentorInformation'])->name('profile.show.mentor.information');
    Route::patch('/profile/mentor/information', [ProfileController::class, 'updateMentorInformation'])->name('profile.update.mentor.information');

    Route::get('/skills', [ProfileController::class, 'showSkills'])->name('skills.index');
});
