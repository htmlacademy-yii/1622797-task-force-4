<?php

namespace taskforce\actions;

use app\models\Tasks;

class OffersAction extends AbstractAction
{
    public string $name = 'Откликнуться на задание';
    protected string $internalName = 'ACTION_OFFERS';
    public string $class = 'button button--blue action-btn';
    public string $dataAction = 'act_response';

    public function getLink(): ?string
    {
        return null;
    }

    public function rightsCheck(Tasks $task, int $userId): bool
    {
        if ($task->status === Tasks::STATUS_NEW && $task->customer_id !== $userId) {
            return true;
        }
        return false;
    }
}
