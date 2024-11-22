<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all()->sortBy('created_at')->partition(function ($task) {
            return ! $task->isCompleted();
        });
        $uncompletedTasks = $tasks[0];
        $completedTasks = $tasks[1];

        return view('task.index')->with([
            'uncompletedTasks' => $uncompletedTasks,
            'completedTasks' => $completedTasks,
        ]);
    }

    public function create()
    {
        return view('task.create');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        return view('task.show')->with('task', $task);
    }

    public function toggleComplete(Request $request, Task $task)
    {
        $task->update([
            'completed_at' => $request->completed ? now() : null,
        ]);

        return response()->noContent();
    }
}
