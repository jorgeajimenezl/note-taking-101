<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SharedTask extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task shared with you')
            ->from(config('mail.from.address'), $this->task->user->name)
            ->greeting('Hello '.$notifiable->name)
            ->line('A task has been shared with you')
            ->action('View Task', route('tasks.show', $this->task->id))
            ->line('Thank you for using our application!');
    }
}
