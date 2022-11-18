<?php

namespace taskforce\actions;

use app\models\Tasks;

class CompleteAction extends AbstractAction
{
    public string $name = 'Завершить задание';
    protected string $internalName = 'ACTION_COMPLETE';
    public string $class = 'button button--pink action-btn';
    public string $dataAction = 'completion';

    public function rightsCheck(Tasks $task, int $userId): bool
    {
        if ($task->status === Tasks::STATUS_AT_WORK && $task->customer_id === $userId) {
            return true;
        }
        return false;
    }

    public function getLink(): ?string
    {
        return null;
    }
}
