<?php

namespace app\controllers;

use app\models\Offers;
use taskforce\exception\TaskActionException;
use Throwable;
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
    /** Метод отвечает за показ страницы с Заданиями
     *
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

    /** Метод отвечает за просмотр страницы с конкретным Заданием
     *
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionView($id): string
    {
        $user = Yii::$app->user->identity;
        $taskCreateForm = new TaskCreateForm();
        $task = Tasks::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задания с ID $id не сущесвует");
        }
        return $this->render('view', [
            'task' => $task,
            'taskCreateForm' => $taskCreateForm,
            'user' => $user
        ]);
    }

    /** Метод отвечает за создание новое Задания
     *
     * @return string|Response
     * @throws ServerErrorHttpException|Throwable
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

    /** Метод загружает файлы из задания пользователю
     *
     * @param $path
     * @return void|null
     */
    public function actionDownload($path)
    {
        return Yii::$app->response->sendFile(Yii::getAlias('@webroot/uploads/') . $path)->send();
    }

    /** Метод назначает исполнителя для задания
     *
     * @param $task
     * @param $user
     * @return void|Response
     */
    public function actionStart($task, $user)
    {
        $task = Tasks::findOne($task);

        if ($task->customer_id === Yii::$app->user->getId()) {
            $task->setExecutor($user);

            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /** Метод отказа исполнителю в участии в Задании
     *
     * @param $task
     * @param $user
     * @return void|Response
     */
    public function actionCancel($task, $user)
    {
        $customer = Tasks::findOne($task)->customer_id;

        if ($customer === Yii::$app->user->getId()) {
            $offers = Offers::find()->andWhere(['task_id' => $task, 'executor_id' => $user])->one();
            $offers->refuse = 1;
            $offers->save();

            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
