<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{
    public function index()
{
    /** @var User $user */
    $user = Auth::user();

    $tasks = $user->tasks()->latest()->get();
    $balance = $user->balance;

    return view('tasks.index', compact('tasks', 'balance'));
}


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'points' => 'required|integer|min:1|max:1000',
    ]);

    /** @var User $user */
    $user = Auth::user();

    $user->tasks()->create([
        'title' => $request->title,
        'points' => $request->points,
    ]);

    return redirect()->route('tasks.index')->with('success', 'Задача добавлена!');
}

    public function complete(Task $task)
{
    if ($task->user_id !== Auth::id() || $task->completed_at) {
        abort(403);
    }

    $task->update(['completed_at' => now()]);

    /** @var User $user */
    $user = Auth::user();
    $user->increment('balance', $task->points);

    return back()->with('success', "✅ {$task->title} выполнено! +{$task->points} баллов.");
}
}