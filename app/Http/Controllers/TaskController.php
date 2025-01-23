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
            'description' => ['required', 'string'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'tags' => ['array'],
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => auth()->id(),
        ]);

        $task->tags()->sync($request->tags);

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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['array'],
            // 'attachments' => ['nullable', 'array'],
        ]);

        $task = Task::find($id);
        $role = $task->getUserRole(auth()->user());

        if ($role !== 'owner' && $role !== 'editor') {
            abort(403);
        }

        if ($task === null) {
            return redirect()->route('tasks.index')->withErrors('Task not found');
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $task->tags()->sync($request->tags);
        // $task->addMultipleMediaFromRequest($request->attachments)->each(function ($fileAdder) {
        //     $fileAdder->toMediaCollection('task_attachments');
        // });
        session()->flash('success', 'Task updated successfully');

        return redirect()->route('tasks.show', $task->id);
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
