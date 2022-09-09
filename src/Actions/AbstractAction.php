<?php

namespace Taskforce\Actions;
namespace Taskforce\Models;

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
