<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('author_id', auth()->id())->paginate(10);
        $sharedTasks = Task::select('tasks.*')
            ->join('contributors', 'tasks.id', '=', 'contributors.task_id')
            ->where('contributors.user_id', auth()->id())
            ->paginate(10);

        return new TaskCollection($tasks->merge($sharedTasks));
    }

    public function show(string $id)
    {
        $task = Task::with('author', 'contributors', 'tags')->findOrFail($id);

        if ($task->getUserRole(auth()->user()) === null) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        if ($task->author_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->noContent();
    }
}
