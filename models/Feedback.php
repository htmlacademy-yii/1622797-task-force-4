<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $executor_id
 * @property int $task_id
 * @property string $date_creation
 * @property string $description
 * @property int $grade
 *
 * @property Users $customer
 * @property Users $executor
 * @property Tasks $task
 */
class Feedback extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'executor_id', 'task_id', 'description', 'grade'], 'required'],
            [['customer_id', 'executor_id', 'task_id', 'grade'], 'integer'],
            [['date_creation'], 'safe'],
            [['description'], 'string'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
                'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
                'targetAttribute' => ['executor_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
                'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'task_id' => 'Task ID',
            'date_creation' => 'Date Creation',
            'description' => 'Description',
            'grade' => 'Grade',
        ];
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
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }
}
