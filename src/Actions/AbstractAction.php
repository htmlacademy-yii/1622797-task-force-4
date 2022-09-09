<?php

namespace Taskforce\Actions;

use Taskforce\Models\Task;

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

    abstract protected function rightsCheck(Task $task, int $currentUserId): bool;
}
