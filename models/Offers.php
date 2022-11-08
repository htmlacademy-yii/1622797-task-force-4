<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "offers".
 *
 * @property int $id
 * @property string|null $date_creation
 * @property int $task_id
 * @property int $executor_id
 * @property int|null $price
 * @property string|null $comment
 * @property string|null $refuse
 *
 * @property Users $executor
 * @property Tasks $task
 */
class Offers extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'offers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date_creation'], 'safe'],
            [['task_id', 'executor_id'], 'required'],
            [['task_id', 'executor_id', 'price', 'refuse'], 'integer'],
            [['comment'], 'string'],
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
            'date_creation' => 'Date Creation',
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'price' => 'Price',
            'comment' => 'Comment',
            'refuse' => 'Refuse',
        ];
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
