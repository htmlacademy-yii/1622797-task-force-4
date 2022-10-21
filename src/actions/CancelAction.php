<?php

namespace taskforce\actions;

use taskforce\models\Task;

class CancelAction extends AbstractAction
{
    protected string $name = 'Отменить';
    protected string $internalName = 'ACTION_CANCEL';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_NEW && $task->customerId === $currentUserId) {
            return true;
        }
        return false;
    }
}
