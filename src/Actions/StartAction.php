<?php

namespace Taskforce\Actions;

class StartAction extends AbstractAction
{
    protected string $name = 'Начать';
    protected string $internalName = 'ACTION_START';

    protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool
    {
        if ($executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}
