<?php

namespace Taskforce\Actions;
namespace Taskforce\Models;

class RefuseAction extends AbstractAction
{
    protected string $name = 'Отказаться';
    protected string $internalName = 'ACTION_REFUSE';

    protected function rightsCheck(Task $task, int $currentUserId): bool
    {
        if ($task->status === Task::STATUS_AT_WORK && $task->executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}