<?php

namespace app\controllers;

use app\models\forms\FeedbackForm;
use app\models\forms\OffersForm;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskFilterForm;
use app\models\Offers;
use app\models\Tasks;
use app\services\TaskCreateService;
use taskforce\actions\CancelAction;
use taskforce\actions\RemoveAction;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
        $newOffers = new OffersForm();
        $feedbackForm = new FeedbackForm();
        $task = Tasks::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задания с ID $id не сущесвует");
        }
        return $this->render('view', [
            'task' => $task,
            'taskCreateForm' => $taskCreateForm,
            'newOffers' => $newOffers,
            'feedbackForm' => $feedbackForm,
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
                $createdTask = new TaskCreateService();
                $createdTask->saveNewTask($taskCreateForm);

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

    /**
     * @param $taskId
     * @param $userId
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionStart($taskId, $userId): Response
    {
        $task = Tasks::findOne($taskId);
        $task->status = Tasks::STATUS_AT_WORK;
        $task->executor_id = $userId;
        $task->update();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /** Метод отказа исполнителю в участии в Задании
     *
     * @param $responseId
     * @return Response
     */
    public function actionRefuse($responseId): Response
    {
        $offers = Offers::findOne($responseId);
        $offers->refuse = 1;
        $offers->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /** Метод создает отзыв исполнителя под Заданием
     *
     * @return string|Response
     */
    public function actionOffers(): string|Response
    {
        $newOffers = new OffersForm();

        if (Yii::$app->request->getIsPost()) {
            $newOffers->load(Yii::$app->request->post());

            if ($newOffers->validate()) {
                $newOffers->createOffers();

                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->redirect('/tasks');
    }

    /**
     * @return Response
     */
    public function actionFeedback(): Response
    {
        $feedback = new FeedbackForm();

        if (Yii::$app->request->getIsPost()) {
            $feedback->load(Yii::$app->request->post());

            if ($feedback->validate()) {
                $feedback->createFeedback();

                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->redirect('/tasks');
    }

    /** Метод для отмены задания его владельцом
     *
     * @param $id
     * @return Response
     */
    public function actionRemove($id): Response
    {
        $task = Tasks::findOne($id);
        $action = new RemoveAction();

        if ($action->rightsCheck($task, Yii::$app->user->identity->id)) {
            $action->removeTask($id);

            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->redirect('/tasks');
    }

    /** Метод для отказа от задания исполнителем
     *
     * @param $id
     * @return Response
     */
    public function actionCancel($id)
    {
        $task = Tasks::findOne($id);
        $action = new CancelAction();

        if ($action->rightsCheck($task, Yii::$app->user->identity->id)) {
            $action->cancelTask($id);

            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->redirect('/tasks');
    }
}
