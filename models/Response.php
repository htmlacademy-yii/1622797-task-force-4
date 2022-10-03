<?php

namespace app\models;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property string|null $date_creation
 * @property int $task_id
 * @property int $executor_id
 * @property int|null $price
 * @property string|null $comment
 * @property int|null $refuse
 *
 * @property Users $executor
 * @property Tasks $task
 */
class Response extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }
}
