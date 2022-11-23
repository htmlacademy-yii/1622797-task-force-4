<?php

namespace app\models;

use taskforce\actions\CancelAction;
use taskforce\actions\CompleteAction;
use taskforce\actions\OffersAction;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $city_id
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $date_creation
 * @property int $category_id
 * @property int $customer_id
 * @property int|null $executor_id
 * @property string $status
 * @property int $budget
 * @property string $period_execution
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $customer
 * @property Users $executor
 * @property Feedback[] $feedbacks
 * @property Offers[] $responses
 * @property TasksFiles[] $tasksFiles
 */
class Tasks extends ActiveRecord
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_AT_WORK = 'work';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'category_id', 'customer_id', 'status', 'budget',
                'period_execution'], 'required'],
            [['description', 'status', 'address', 'latitude',
                'longitude'], 'string'],
            [['city_id', 'category_id', 'customer_id', 'executor_id',
                'budget'], 'integer'],
            [['date_creation', 'period_execution'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['executor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Categories::class,
                'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Cities::class,
                'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'city_id' => 'City ID',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'date_creation' => 'Date Creation',
            'category_id' => 'Category ID',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'status' => 'Status',
            'budget' => 'Budget',
            'period_execution' => 'Period Execution',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return ActiveQuery
     */
    public function getFeedbacks(): ActiveQuery
    {
        return $this->hasMany(Feedback::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getOffers(): ActiveQuery
    {
        return $this->hasMany(Offers::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TasksFiles]].
     *
     * @return ActiveQuery
     */
    public function getTasksFiles(): ActiveQuery
    {
        return $this->hasMany(TasksFiles::class, ['task_id' => 'id']);
    }

    /** Метод содержит все названия статусов задания
     *
     * @return string[]
     */
    private function getTaskStatusesList(): array
    {
        return
            [
                self::STATUS_NEW => 'Задание опубликовано, исполнитель ещё не найден',
                self::STATUS_CANCELLED => 'Заказчик отменил задание',
                self::STATUS_AT_WORK => 'Заказчик выбрал исполнителя для задания',
                self::STATUS_DONE => 'Заказчик отметил задание как выполненное',
                self::STATUS_FAILED => 'Исполнитель отказался от выполнения задания'
            ];
    }

    /** Метод для получения текущего статутса задания
     *
     * @return string
     */
    public function getStatusName(): string
    {
        $statusList = $this->getTaskStatusesList();
        return $statusList[$this->status];
    }

    /** Метод получает название категорий заданий для пользователей
     *
     * @return string[]
     */
    public static function getStatusesLabels(): array
    {
        return [
            self::STATUS_NEW => 'Новые задание',
            self::STATUS_AT_WORK => 'Задания в процессе',
            self::STATUS_DONE => 'Закрытые задания',
            self::STATUS_FAILED => 'Просрочено',
        ];
    }

    /** Метод получает все новые задачи Заказчика
     *
     * @param $userId
     * @return ActiveQuery
     */
    public function getNewCustomerTasks($userId): ActiveQuery
    {
        $tasksQuery = Tasks::find();
        return $tasksQuery->where(['customer_id' => $userId])
            ->andWhere(['status' => Tasks::STATUS_NEW]);
    }

    /** Метод получает все задачи в работе в Исполнителя или Заказчика
     *
     * @param $userId
     * @return ActiveQuery
     */
    public function getInWorkTasks($userId): ActiveQuery
    {
        $tasksQuery = Tasks::find();
        if ($userId->is_executor !== 1) {
            return $tasksQuery->where(['customer_id' => $userId])
                ->andWhere(['status' => Tasks::STATUS_AT_WORK]);
        } else {
            return $tasksQuery->where(['executor_id' => $userId])
                ->andWhere(['status' => Tasks::STATUS_AT_WORK]);
        }
    }

    /** Метод получает закрытые и проваленные задачи для Исполнителя и Заказчика
     *
     * @param $userId
     * @return ActiveQuery
     */
    public function getDoneTasks($userId): ActiveQuery
    {
        $tasksQuery = Tasks::find();
        if ($userId->is_executor !== 1) {
            return $tasksQuery->where(['customer_id' => $userId])
                ->andWhere(['status' => [Tasks::STATUS_FAILED, Tasks::STATUS_DONE,
                    Tasks::STATUS_CANCELLED]]);
        } else {
            return $tasksQuery->where(['executor_id' => $userId])
                ->andWhere(['status' => [Tasks::STATUS_DONE, Tasks::STATUS_FAILED]]);
        }
    }

    /** Метод получает просроченные задачи Исполнителя
     *
     * @param $userId
     * @return ActiveQuery
     */
    public function getDeadlineExecutorTasks($userId): ActiveQuery
    {
        $tasksQuery = Tasks::find();
        return $tasksQuery->where(['executor_id' => $userId])
            ->andWhere(['period_execution' => '< NOW()']);
    }

    /** Метод выполняет действие по Отмене задание его создателем
     *
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function removeTask(): bool
    {
        if ($this->customer_id !== Yii::$app->user->id || $this->status !== self::STATUS_NEW) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        $this->update(false);

        return true;
    }

    /** Метод выполняет действие по Отказу от выполнения задания исполнителем
     *
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function cancelTask(): bool
    {
        if ($this->executor_id !== Yii::$app->user->id || $this->status !== self::STATUS_AT_WORK) {
            return false;
        }

        $this->status = self::STATUS_FAILED;
        return $this->update(false);
    }

    /** Метод выполняет действие по назначению исполнителя и старта задания
     *
     * @param $userId
     * @return bool
     */
    public function startTask($userId): bool
    {
        if ($this->customer_id !== Yii::$app->user->id) {
            return false;
        }

        $this->status = self::STATUS_AT_WORK;
        $this->executor_id = $userId;
        $this->save(false);

        return true;
    }

    /** Метод выполняет действие по отказу исполнителю в выполнении задания
     *
     * @param $responseId
     * @return bool
     */
    public function refuseExecutor($responseId): bool
    {
        if ($this->customer_id !== Yii::$app->user->id) {
            return false;
        }

        $offers = Offers::findOne($responseId);
        $offers->refuse = 1;
        $offers->save();

        return true;
    }
}
