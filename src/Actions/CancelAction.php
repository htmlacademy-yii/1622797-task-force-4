<?php

namespace Taskforce\Actions;

class CancelAction extends AbstractAction
{
    protected string $name = 'Отменить';
    protected string $internalName = 'ACTION_CANCEL';

    protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool
    {
        if ($customerId === $currentUserId) {
            return true;
        }
        return false;
    }
}
