<?php

namespace taskforce\actions;

use app\models\Tasks;

class RemoveAction extends AbstractAction
{
    public string $name = 'Отменить задание';
    protected string $internalName = 'ACTION_REMOVE';
    public string $class = 'button button--orange action-btn';
    public string $dataAction = 'refusal';

    public function getLink(): ?string
    {
        return null;
    }

    public function rightsCheck(Tasks $task, int $userId): bool
    {
        if ($task->status === Tasks::STATUS_NEW && $task->customer_id === $userId) {
            return true;
        }
        return false;
    }

    public function removeTask($task)
    {
        $task = Tasks::findOne($task);
        $task->status = Tasks::STATUS_CANCELLED;
        $task->save();
    }
}
