<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => ['string'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'tags' => ['array'],
            'attachments' => ['array', 'max:5'], // max 5 files
            'attachments.*' => ['bail', 'file', 'max:10240'], // 10MB
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => auth()->id(),
        ]);

        $task->tags()->sync($request->tags);
        if ($request->has('attachments')) {
            $task->addMultipleMediaFromRequest(['attachments'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('attachments');
                });
        }

        return redirect()->route('tasks.index');
    }

    public function show(int $id)
    {
        $user = auth()->user();

        $task = Task::findOrFail($id);
        $role = $task->getUserRole($user);

        if ($role === null) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

    public function toggleComplete(Request $request, Task $task)
    {
        $request->validate([
            'completed' => ['required', 'boolean'],
        ]);

        if ($task->author_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'completed_at' => $request->completed ? now() : null,
        ]);

        return response()->noContent();
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->author_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
