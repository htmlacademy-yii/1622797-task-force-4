<?php

namespace app\services;

use app\models\Files;
use app\models\TasksFiles;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class FileService
{
    /** Метод загружает файлы в БД
     *
     * @param UploadedFile $file
     * @return Files|null
     * @throws ServerErrorHttpException
     */
    public function uploadNewFile(UploadedFile $file): ?Files
    {
        $savedFile = new Files();
        $nameFile = uniqid('taskfile') . '.' . $file->getExtension();
        $file->saveAs('@webroot/uploads/' . $nameFile);
        $savedFile->url = $nameFile;

        if (!$savedFile->save(false)) {
            throw new ServerErrorHttpException('Ваши файлы не удалость загрузить');
        }

        return $savedFile;
    }

    /** Метод сохраняет связь между файлом и заданием
     *
     * @param int $fileId
     * @param int $taskId
     * @return TasksFiles|null
     * @throws ServerErrorHttpException
     */
    public function saveTaskFiles(int $fileId, int $taskId): ?TasksFiles
    {
        $taskFile = new TasksFiles();
        $taskFile->task_id = $taskId;
        $taskFile->file_id = $fileId;

        if (!$taskFile->save(false)) {
            throw new ServerErrorHttpException('Ваши файлы не удалось загрузить');
        }

        return $taskFile;
    }
}
