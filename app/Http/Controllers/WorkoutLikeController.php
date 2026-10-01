<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

class WorkoutLikeController extends Controller
{
    public function store(Workout $workout)
    {
        // 自分の記録にはナイスバルク！できない
        abort_if(Auth::user()->is($workout->user), 403);

        // すでに押していれば何もしない（連打対策）
        $workout->liked()->syncWithoutDetaching([Auth::id()]);

        return back();
    }

    public function destroy(Workout $workout)
    {
        $workout->liked()->detach(Auth::id());

        return back();
    }
}