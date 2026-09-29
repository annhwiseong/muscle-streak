<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/schedule', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('/schedule', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::resource('workouts', WorkoutController::class);

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
