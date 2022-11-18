<?php

namespace app\services;

use app\models\forms\TaskCreateForm;
use app\models\Tasks;
use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class TaskCreateService
{
    /** Метод создает и сохраняет новое Задание
     *
     * @param TaskCreateForm $form
     * @return int|null
     * @throws ServerErrorHttpException
     */
    public function saveNewTask(TaskCreateForm $form): ?int
    {
        $task = new Tasks();
        $task->name = $form->taskName;
        $task->description = $form->taskDescriptions;
        $task->category_id = $form->category;
        $task->customer_id = Yii::$app->user->identity->id;
        $task->budget = $form->budget;
        $task->period_execution = $form->periodExecution;
        $task->latitude = $form->latitude;
        $task->longitude = $form->longitude;
        $task->address = $form->location;
        $task->save();

        if (!$task->save(false)) {
            throw new ServerErrorHttpException('Не удалось создать создание');
        }

        $fileService = new FileService();
        foreach (UploadedFile::getInstances($task, 'taskFiles') as $files) {
            $savedFile = $fileService->uploadNewFile($files);
            $fileService->saveTaskFiles($savedFile->id, $task->id);

            return null;
        }
        return $task->id;
    }
}
