<?php

namespace taskforce\actions;

use taskforce\models\Task;

class StartAction extends AbstractAction
{
    protected string $name = 'Начать';
    protected string $internalName = 'ACTION_START';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_NEW && $task->customerId === $currentUserId) {
            return true;
        }
        return false;
    }
}
