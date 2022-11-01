<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\forms\TaskFilterForm;
use app\models\Tasks;
use app\models\forms\TaskCreateForm;
use taskforce\models\TaskCreate;
use taskforce\models\SaveFile;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class TasksController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $taskFilterForm = new TaskFilterForm();
        $taskQuery = $taskFilterForm->getNewTaskQuery();

        if (Yii::$app->request->getIsGet()) {
            if ($taskFilterForm->load(Yii::$app->request->get())) {
                $taskQuery = $taskFilterForm->getFilteredTasks();
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $taskQuery,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_creation' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'taskFilterForm' => $taskFilterForm]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $taskCreateForm = new TaskCreateForm();
        $task = Tasks::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задания с ID $id не сущесвует");
        }
        return $this->render('view', ['task' => $task,
            'taskCreateForm' => $taskCreateForm]);
    }

    /**
     * @return string|Response
     * @throws ServerErrorHttpException|\Throwable
     */
    public function actionCreate(): Response|string
    {
        $user = Yii::$app->user->getIdentity();
        if ($user->is_executor === 1) {
            return $this->redirect('/tasks');
        }

        $taskCreateForm = new TaskCreateForm();

        if (Yii::$app->request->getIsPost()) {
            $taskCreateForm->load(Yii::$app->request->post());

            if ($taskCreateForm->validate()) {
                $createdTask = TaskCreate::saveNewTasks($taskCreateForm);
                foreach (UploadedFile::getInstances($taskCreateForm, 'taskFiles') as $files) {
                    $savedFile = saveFile::uploadNewFile($files);
                    saveFile::saveTaskFiles($savedFile->id, $createdTask->id);
                }
                return $this->redirect(['view', 'id' => $createdTask->id]);
            }
        }
        return $this->render('create', ['taskCreateForm' => $taskCreateForm]);
    }

    /** Метод загружает файлы задания польователю
     *
     * @param $path
     * @return void|null
     */
    public function actionDownload($path)
    {
        return Yii::$app->response->sendFile(Yii::getAlias('@webroot/uploads/') . $path)->send();
    }
}
