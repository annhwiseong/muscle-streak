<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== 自分の状況 =====
        $streak        = $user->scheduleStreak();
        $todaySchedule = $user->todaySchedule();
        $trainedToday  = $user->trainedToday();
        $hasSchedule   = $user->schedules->isNotEmpty();
        $todayLabel    = Schedule::DAYS[today()->dayOfWeek];

        // ===== 仲間の状況 =====
        // フォロー中のユーザーと、その予定・記録をまとめて取得（N+1 対策）
        $friends = $user->follows()
            ->with(['schedules', 'workouts:id,user_id,trained_on'])
            ->get();

        // 1人ずつストリークを計算して持たせておき、多い順に並べる
        $friends->each(fn ($friend) => $friend->streak = $friend->scheduleStreak());
        $friends = $friends->sortByDesc('streak')->values();

        // 今日が予定日なのに、まだ記録していない仲間
        $notYetFriends = $friends->filter(
            fn ($friend) => $friend->todaySchedule() && ! $friend->trainedToday()
        );

        return view('dashboard', compact(
            'streak', 'todaySchedule', 'trainedToday', 'hasSchedule', 'todayLabel',
            'friends', 'notYetFriends'
        ));
    }
}