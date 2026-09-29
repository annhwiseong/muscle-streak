<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $streak = $user->scheduleStreak();

        // 今日の曜日の予定（なければ null ＝ 休養日）
        $todaySchedule = $user->schedules->firstWhere('day_of_week', today()->dayOfWeek);

        // 今日すでに記録したか
        $trainedToday = $user->workouts()->whereDate('trained_on', today())->exists();

        // 今日の曜日の表示名（例：火）
        $todayLabel = Schedule::DAYS[today()->dayOfWeek];

        // 予定が1つもないか
        $hasSchedule = $user->schedules->isNotEmpty();

        return view('dashboard', compact('streak', 'todaySchedule', 'trainedToday', 'todayLabel', 'hasSchedule'));
    }
}