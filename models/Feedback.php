<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $executor_id
 * @property int $task_id
 * @property string $date_creation
 * @property string $description
 * @property int $rating
 *
 * @property Users $customer
 * @property Users $executor
 * @property Tasks $task
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'executor_id', 'task_id', 'description', 'rating'], 'required'],
            [['customer_id', 'executor_id', 'task_id', 'rating'], 'integer'],
            [['date_creation'], 'safe'],
            [['description'], 'string'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class(),
                'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class(),
                'targetAttribute' => ['executor_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class(),
                'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'task_id' => 'Task ID',
            'date_creation' => 'Date Creation',
            'description' => 'Description',
            'rating' => 'Rating',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::class(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class(), ['id' => 'task_id']);
    }
}
