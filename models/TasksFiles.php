<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasksFiles".
 *
 * @property int $id
 * @property int $task_id
 * @property int $file_id
 *
 * @property Files $file
 * @property Tasks $task
 */
class TasksFiles extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasksFiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'file_id'], 'required'],
            [['task_id', 'file_id'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
                'targetAttribute' => ['task_id' => 'id']],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class,
                'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'file_id' => 'File ID'
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return ActiveQuery
     */
    public function getFile(): ActiveQuery
    {
        return $this->hasOne(Files::class, ['id' => 'file_id']);
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
