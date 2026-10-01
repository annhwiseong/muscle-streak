<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\WorkoutLikeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
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
    Route::resource('workouts.comments', CommentController::class)->scoped();
    Route::post('/workouts/{workout}/like', [WorkoutLikeController::class, 'store'])->name('workouts.like');
    Route::delete('/workouts/{workout}/like', [WorkoutLikeController::class, 'destroy'])->name('workouts.dislike');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/follow/{user}', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user}', [FollowController::class, 'destroy'])->name('follow.destroy');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
