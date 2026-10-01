<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user)
    {
        // 予定・フォロー・フォロワーをまとめて読み込む
        $user->load(['schedules', 'follows', 'followers']);

        $streak = $user->scheduleStreak();

        // このユーザーの記録（新しい順・10件ずつ）
        $workouts = $user->workouts()
            ->latest('trained_on')
            ->latest()
            ->paginate(10);

        // ログイン中の自分がこのユーザーをフォローしているか
        $isFollowing = $user->followers->contains(Auth::id());

        return view('users.show', compact('user', 'streak', 'workouts', 'isFollowing'));
    }
}