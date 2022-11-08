<?php

namespace taskforce\actions;

use app\models\Tasks;

abstract class AbstractAction
{
    public string $name;
    protected string $internalName;
    public string $class;
    public string $dataAction;

    public function getName(): string
    {
        return $this->name;
    }

    public function getInternalname(): string
    {
        return $this->internalName;
    }

    abstract public function rightsCheck(Tasks $task, int $userId): bool;

    abstract public function getLink(): ?string;
}
