<?php

namespace Taskforce\Actions;

class RefuseAction extends AbstractAction
{
    protected string $name = 'Отказаться';
    protected string $internalName = 'ACTION_REFUSE';

    protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool
    {
        if ($executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}
