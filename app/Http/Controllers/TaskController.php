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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $task = Task::find($id);

        if ($task) {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            session()->flash('success', $task->title);

            return redirect()->route('task.show', $task->id);
        } else {
            return redirect()->route('task.index')->withErrors('Task not found');
        }
    }
}
