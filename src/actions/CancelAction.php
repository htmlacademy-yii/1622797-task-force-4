<?php

namespace taskforce\actions;

use app\models\Tasks;
use Yii;

class CancelAction extends AbstractAction
{
    public string $name = 'Отказаться от задания';
    protected string $internalName = 'ACTION_CANCEL';
    public string $class = 'button button--orange action-btn';
    public string $dataAction = 'refusal';

    public function getLink(): ?string
    {
        return null;
    }

    public function rightsCheck(Tasks $task, int $userId): bool
    {
        if ($task->status === Tasks::STATUS_AT_WORK && $task->executor_id === $userId) {
            return true;
        }
        return false;
    }

    public function cancelTask($task)
    {
        $task = Tasks::findOne($task);
        $task->status = Tasks::STATUS_FAILED;
        $task->executor_id = Yii::$app->user->identity->id;
        $task->update();
    }
}
