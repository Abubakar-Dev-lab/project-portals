<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        if ($task->assigned_to) {
            $task->load('project');
            $task->user->notify(new TaskAssignedNotification($task));
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->wasChanged('assigned_to') && $task->assigned_to) {
            $task->load('project');
            $task->user->notify(new TaskAssignedNotification($task));
        }
    }
}
