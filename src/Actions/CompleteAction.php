<?php

namespace Taskforce\Actions;
namespace Taskforce\Models;

class CompleteAction extends AbstractAction
{
    protected string $name = 'Выполнено';
    protected string $internalName = 'ACTION_COMPLETE';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_NEW && $task->customerId === $currentUserId) {
            return true;
        }
        if ($task->status === Task::STATUS_AT_WORK && $task->executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}
