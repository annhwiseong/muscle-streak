<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::with('user')
            ->latest('trained_on') // トレーニング日の新しい順
            ->latest()             // 同じ日なら作成が新しい順
            ->get();

        return view('workouts.index', compact('workouts'));
    }

    public function create()
    {
        return view('workouts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trained_on' => 'required|date|before_or_equal:today',
            'body_part'  => ['required', Rule::in(Workout::BODY_PARTS)],
            'memo'       => 'nullable|string|max:1000',
        ]);

        $request->user()->workouts()->create($validated);

        return redirect()->route('workouts.index');
    }

    public function show(Workout $workout)
    {
        return view('workouts.show', compact('workout'));
    }

    public function edit(Workout $workout)
    {
        abort_if($workout->user_id !== Auth::id(), 403);

        return view('workouts.edit', compact('workout'));
    }

    public function update(Request $request, Workout $workout)
    {
        abort_if($workout->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'trained_on' => 'required|date|before_or_equal:today',
            'body_part'  => ['required', Rule::in(Workout::BODY_PARTS)],
            'memo'       => 'nullable|string|max:1000',
        ]);

        $workout->update($validated);

        return redirect()->route('workouts.show', $workout);
    }

    public function destroy(Workout $workout)
    {
        abort_if($workout->user_id !== Auth::id(), 403);

        $workout->delete();

        return redirect()->route('workouts.index');
    }
}