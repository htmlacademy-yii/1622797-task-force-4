<?php

namespace taskforce\actions;

use app\models\Tasks;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

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

    public function rightsCheckCustomer(Tasks $task, int $userId): bool
    {
        if ($task->status === Tasks::STATUS_NEW && $task->customer_id === $userId) {
            return true;
        }
        return false;
    }

    /**
     * @param $task
     * @return void
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function cancelTask($task): void
    {
        $task = Tasks::findOne($task);
        $task->status = Tasks::STATUS_FAILED;
        $task->executor_id = Yii::$app->user->identity->id;
        $task->update();
    }

    /**
     * @param $task
     * @return void
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function removeTask($task): void
    {
        $task = Tasks::findOne($task);
        $task->status = Tasks::STATUS_CANCELLED;
        $task->update();
    }
}
