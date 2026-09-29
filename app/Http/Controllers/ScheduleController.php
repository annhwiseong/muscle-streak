<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Workout;
use Illuminate\Validation\Rule;


class ScheduleController extends Controller
{
    public function edit()
    {
        // 自分の予定を「曜日の番号」をキーにして取得（例：[1 => 月曜の予定, 3 => 水曜の予定]）
        $schedules = Auth::user()->schedules->keyBy('day_of_week');

        return view('schedule.edit', compact('schedules'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'days'         => 'array',
            'days.*'       => 'integer|between:0,6',
            'body_parts'   => 'array',
            'body_parts.*' => ['nullable', Rule::in(Workout::BODY_PARTS)],
        ]);

        $user = $request->user();
        $days = $request->input('days', []);

        $user->schedules()->whereNotIn('day_of_week', $days)->delete();

        foreach ($days as $day) {
            $user->schedules()->updateOrCreate(
                ['day_of_week' => $day],
                ['body_part' => $request->input("body_parts.$day")]
            );
        }

        return redirect()->route('schedule.edit')->with('status', '予定を保存しました');
    }
}