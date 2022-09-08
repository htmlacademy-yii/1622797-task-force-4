<?php

namespace Taskforce\Actions;

abstract class AbstractAction
{
    protected string $name;
    protected string $internalName;

    public function getName(): string
    {
        return $this->name;
    }

    public function getInternalname(): string
    {
        return $this->internalName;
    }

    abstract protected function rightsCheck(int $executorId, int $customerId, int $currentUserId): bool;
}
