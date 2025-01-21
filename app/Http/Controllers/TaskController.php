<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $ownTasks = Task::where('author_id', auth()->id())
            ->get()
            ->sortBy('created_at')
            ->partition(function ($task) {
                return ! $task->isCompleted();
            });

        $sharedTasks = Task::select(
            ['tasks.id', 'tasks.title', 'tasks.slug', 'contributors.role as user_role']
        )
            ->join('contributors', 'tasks.id', '=', 'contributors.task_id')
            ->where('contributors.user_id', auth()->id())
            ->get()
            ->sortBy('created_at');

        return view('tasks.index')->with([
            'uncompletedTasks' => $ownTasks[0],
            'completedTasks' => $ownTasks[1],
            'sharedTasks' => $sharedTasks,
        ]);
    }

    public function create()
    {
        $allTags = Tag::where('user_id', auth()->id())->get();

        return view('tasks.create', compact('allTags'));
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
            'slug' => Task::generateRandomSlug(),
        ]);

        $task->tags()->sync($request->tags);

        return redirect()->route('tasks.index');
    }

    public function show(string $slug)
    {
        $user = auth()->user();

        $task = Task::where('slug', $slug)->firstOrFail();
        $allTags = Tag::where('user_id', auth()->id())->get();
        $role = $task->getUserRole($user);

        if ($role === null) {
            abort(403);
        }

        return view('tasks.show', compact('task', 'allTags', 'role'));
    }

    public function toggleComplete(Request $request, string $slug)
    {
        $request->validate([
            'completed' => ['required', 'boolean'],
        ]);

        $task = Task::where('slug', $slug)->firstOrFail();

        if ($task->author_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'completed_at' => $request->completed ? now() : null,
        ]);

        return response()->noContent();
    }

    public function update(Request $request, string $slug)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['array'],
            // 'attachments' => ['nullable', 'array'],
        ]);

        $task = Task::where('slug', $slug)->first();
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

        return redirect()->route('tasks.show', $task->slug);
    }

    public function destroy(string $slug)
    {
        $task = Task::where('slug', $slug)->first();

        if ($task->author_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
