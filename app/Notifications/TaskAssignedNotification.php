<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class TaskAssignedNotification extends Notification
{
    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // or just 'database'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You have been assigned a task')
            ->line("Task: {$this->task->name}")
            ->line('You have been assigned to a new task.')
            ->action('View Task', url("/tasks/{$this->task->id}"));
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
        ];
    }
}


