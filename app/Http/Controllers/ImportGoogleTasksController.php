<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\GoogleTasksService;
use Illuminate\Http\Request;

class ImportGoogleTasksController extends Controller
{
    public function show(Request $request, GoogleTasksService $service)
    {
        $access_token = $request->session()->pull('google.access_token');

        if ($access_token === null) {
            redirect()->setIntendedUrl(route('import.google-tasks'));

            return redirect()->route('oauth.google');
        }

        try {
            $service->setAccessToken($access_token);
            $tasks = $service->fetchTasks();
        } catch (\Exception $e) {
            ray($e)->red();

            return back()->with('status', 'error:tasks-fetch-failed');
        }

        cache()->put(
            'google.tasks.'.auth()->id(),
            $tasks,
            now()->addMinutes(5),
        );

        return view('import.google-tasks', [
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*' => 'string',
        ]);

        ray($request->tasks);

        $task_set = [];
        foreach ($request->tasks as $task) {
            $task_set[$task] = true;
        }

        $tasks = cache()->pull('google.tasks.'.auth()->id());

        if ($tasks === null) {
            return redirect()->route('tasks.index')
                ->with('status', 'error:tasks-fetch-expired');
        }

        try {
            foreach ($tasks as $task) {
                if (! isset($task_set[$task->id])) {
                    continue;
                }

                Task::create([
                    'author_id' => auth()->id(),
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => $task->due_date,
                    'completed_at' => $task->completed_at,
                ]);
            }
        } catch (\Exception $e) {
            ray($e)->red();

            return redirect()->route('tasks.index')
                ->with('status', 'error:tasks-import-failed');
        }

        return redirect()->route('tasks.index')
            ->with('status', 'tasks-imported');
    }
}
