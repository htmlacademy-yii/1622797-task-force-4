<?php

namespace Taskforce\Actions;

class RespondAction extends AbstractAction
{
    protected string $name = 'Откликнуться';
    protected string $internalName = 'ACTION_RESPOND';

    protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool
    {
        if ($executorId === $currentUserId) {
            return true;
        }
        return false;
    }
}
