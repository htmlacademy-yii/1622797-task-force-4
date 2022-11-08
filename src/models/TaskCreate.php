<?php

namespace taskforce\models;

use Yii;
use app\models\forms\TaskCreateForm;
use app\models\Tasks;
use yii\web\ServerErrorHttpException;

class TaskCreate
{
    /** Метод создает и сохраняет новое Задание
     *
     * @param TaskCreateForm $form
     * @return Tasks|null
     * @throws ServerErrorHttpException
     */
    public static function saveNewTasks(TaskCreateForm $form): ?Tasks
    {
        $task = new Tasks();
        $task->name = $form->taskName;
        $task->description = $form->taskDescriptions;
        $task->category_id = $form->category;
        $task->customer_id = Yii::$app->user->identity->id;
        $task->budget = $form->budget;
        $task->period_execution = $form->periodExecution;
        $task->save();

        if (!$task->save(false)) {
            throw new ServerErrorHttpException('Не удалось создать создание');
        }

        return $task;
    }
}
