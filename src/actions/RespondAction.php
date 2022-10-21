<?php

namespace Taskforce\actions;

use Taskforce\models\Task;

class RespondAction extends AbstractAction
{
    protected string $name = 'Откликнуться';
    protected string $internalName = 'ACTION_RESPOND';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_NEW && $task->executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}
