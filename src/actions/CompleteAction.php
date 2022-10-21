<?php

namespace taskforce\actions;

use taskforce\models\Task;

class CompleteAction extends AbstractAction
{
    protected string $name = 'Выполнено';
    protected string $internalName = 'ACTION_COMPLETE';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_AT_WORK && $task->customerId === $currentUserId) {
            return true;
        }
        return false;
    }
}
