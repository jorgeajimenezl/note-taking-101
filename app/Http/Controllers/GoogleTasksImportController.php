<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Google\Service\Tasks as TaskService;
use Google_Client;
use Illuminate\Http\Request;
use Throwable;

class GoogleTasksImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $client = new Google_Client([
            'scopes' => ['https://www.googleapis.com/auth/tasks.readonly'],
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
        ]);

        $token = $request->session()->pull('google_token');
        $previous_url = $request->session()->pull(
            'previous.url',
            url()->previous(),
        );

        if ($token !== null) {
            $client->setAccessToken($token);
        } else {
            redirect()->setIntendedUrl(route('google.tasks.import'));
            $request->session()->put('previous.url', $previous_url);

            return redirect()->route('oauth.google.redirect');
        }

        try {
            $service = new TaskService($client);
            $tasks = $service->tasks->listTasks('@default')->getItems();

            foreach ($tasks as $task) {
                if ($task->getTitle() === '') {
                    continue;
                }

                Task::create([
                    'author_id' => auth()->id(),
                    'title' => $task->getTitle() ?? 'Untitled',
                    'description' => $task->getNotes() ?? '',
                    'due_date' => $task->getDue(),
                    'completed_at' => $task->getCompleted(),
                ]);
            }

            return redirect($previous_url)
                ->with('success', 'Google tasks imported successfully.');
        } catch (Throwable $e) {
            return redirect($previous_url)
                ->with('error', 'Failed to import Google tasks.');
        } finally {
            $client->revokeToken();
        }
    }
}
