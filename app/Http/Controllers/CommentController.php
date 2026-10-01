<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Workout $workout)
    {
        // 一覧は記録の詳細画面に表示するので、詳細画面へ移動させる
        return redirect()->route('workouts.show', $workout);
    }

    public function create(Workout $workout)
    {
        return view('workouts.comments.create', compact('workout'));
    }

    public function store(Request $request, Workout $workout)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $workout->comments()->create([
            'comment' => $request->comment,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('workouts.show', $workout);
    }

    public function show(Workout $workout, Comment $comment)
    {
        return view('workouts.comments.show', compact('workout', 'comment'));
    }

    public function edit(Workout $workout, Comment $comment)
    {
        abort_unless(Auth::user()->is($comment->user), 403);

        return view('workouts.comments.edit', compact('workout', 'comment'));
    }

    public function update(Request $request, Workout $workout, Comment $comment)
    {
        abort_unless(Auth::user()->is($comment->user), 403);

        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $comment->update($request->only('comment'));

        return redirect()->route('workouts.comments.show', [$workout, $comment]);
    }

    public function destroy(Workout $workout, Comment $comment)
    {
        abort_unless(Auth::user()->is($comment->user), 403);

        $comment->delete();

        return redirect()->route('workouts.show', $workout);
    }
}