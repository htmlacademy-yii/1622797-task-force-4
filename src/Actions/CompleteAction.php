<?php

namespace Taskforce\Actions;

class CompleteAction extends AbstractAction
{
    protected string $name = 'Выполнено';
    protected string $internalName = 'ACTION_COMPLETE';

    protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool
    {
        if ($customerId === $currentUserId) {
            return true;
        }
        return false;
    }
}
