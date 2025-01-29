<?php

namespace App\Services;

use Google\Client as Google_Client;
use Google\Service\Tasks as TaskService;

class GoogleTasksService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client([
            'scopes' => ['https://www.googleapis.com/auth/tasks.readonly'],
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
        ]);
    }

    public function setAccessToken(string $access_token): void
    {
        $this->client->setAccessToken($access_token);
    }

    public function fetchTasks(?int $limit = null): array
    {
        $service = new TaskService($this->client);
        $res = null;
        $tasks = [];

        do {
            $res = $service->tasks->listTasks('@default', [
                'maxResults' => min($limit ?? 100, 100),
                'pageToken' => $res?->nextPageToken,
            ]);
            $items = $res->getItems();

            if ($items === null) {
                break;
            }

            foreach ($items as $task) {
                $tasks[] = (object) [
                    'id' => $task->getId(),
                    'title' => $task->getTitle(),
                    'description' => $task->getNotes(),
                    'due_date' => $task->getDue(),
                    'completed_at' => $task->getCompleted(),
                    'link' => $task->getWebViewLink(),
                ];

                if ($limit !== null) {
                    $limit--;
                }
            }
        } while ($res->nextPageToken !== null);

        return $tasks;
    }

    public function revokeToken(): void
    {
        $this->client->revokeToken();
    }
}
