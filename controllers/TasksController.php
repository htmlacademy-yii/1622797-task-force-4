<?php

namespace app\controllers;

use app\models\forms\FeedbackForm;
use app\models\forms\OffersForm;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskFilterForm;
use app\models\Offers;
use app\models\Tasks;
use app\services\GeocoderService;
use app\services\TaskCreateService;
use taskforce\actions\CancelAction;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class TasksController extends SecuredController
{
    /**
     * @return array|array[]
     */
    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create', 'feedback', 'refuse', 'start', 'remove'],
            'matchCallback' => function ($rule, $action) {
                $isCustomer = Yii::$app->user->getIdentity()->is_executor === 0;
                return empty($isCustomer);
            }
        ];
        $ruleOffer = [
            'allow' => false,
            'actions' => ['offers', 'cancel'],
            'matchCallback' => function ($rule, $action) {
                $isExecutor = Yii::$app->user->getIdentity()->is_executor === 1;
                return empty($isExecutor);
            }
        ];

        array_unshift($rules['access']['rules'], $ruleOffer);
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

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
                $geocoder = new GeocoderService();
                $taskCreateForm->latitude = $geocoder->getLatitude($taskCreateForm->location);
                $taskCreateForm->longitude = $geocoder->getLongitude($taskCreateForm->location);
                $taskCreateForm->location = $geocoder->getAddress($taskCreateForm->location);

                $createdTask = new TaskCreateService();
                $taskId = $createdTask->saveNewTask($taskCreateForm);

                return $this->redirect(['tasks/view', 'id' => $taskId]);
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

    /** Метод назначает исполнителя для Задания
     *
     * @param $taskId
     * @param $userId
     * @return Response
     * @throws Throwable
     */
    public function actionStart($taskId, $userId): Response
    {
        $task = Tasks::findOne($taskId);
        $task->startTask($userId);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /** Метод отказа исполнителю в участии в Задании
     *
     * @param $taskId
     * @param $responseId
     * @return Response
     */
    public function actionRefuse($taskId, $responseId): Response
    {
        $task = Tasks::findOne($taskId);
        $task->refuseExecutor($responseId);

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

    /** Метод оставляет отзыв о работе исполнителя и завершает задание
     *
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

    /** Метод отменяет Задание создателем и переводит все отзывы в статус Отказано
     *
     * @param $id
     * @return Response
     * @throws Throwable
     */
    public function actionRemove($id): Response
    {
        $task = Tasks::findOne($id);
        $task->removeTask();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /** Метод отказа от Задания исполнителем
     *
     * @param $id
     * @return Response
     * @throws Throwable
     */
    public function actionCancel($id): Response
    {
        $task = Tasks::findOne($id);
        $task->cancelTask();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /** Метод отвечает за отрисовку страницы Мои задания
     *
     * @param string $status
     * @return string
     * @throws Throwable
     */
    public function actionMyTasks(string $status): string
    {
        $userId = Yii::$app->user->getIdentity();
        $task = new Tasks();
        $taskQuery = Tasks::find();

        if ($status === Tasks::STATUS_NEW) {
            $taskQuery = $task->getNewCustomerTasks($userId);
        }
        if ($status === Tasks::STATUS_AT_WORK) {
            $taskQuery = $task->getInWorkTasks($userId);
        }
        if ($status === Tasks::STATUS_DONE) {
            $taskQuery = $task->getDoneTasks($userId);
        }
        if ($status === Tasks::STATUS_FAILED) {
            $taskQuery = $task->getDeadlineExecutorTasks($userId);
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

        return $this->render('my-tasks', [
            'dataProvider' => $dataProvider,
            'status' => $status]);
    }
}
