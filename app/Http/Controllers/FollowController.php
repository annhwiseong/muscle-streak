<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(User $user)
    {
        // 自分自身はフォローできない
        abort_if($user->id === Auth::id(), 403);

        // すでにフォロー済みなら何もしない（二重登録エラーを防ぐ）
        Auth::user()->follows()->syncWithoutDetaching([$user->id]);

        return back();
    }

    public function destroy(User $user)
    {
        Auth::user()->follows()->detach($user->id);

        return back();
    }
}