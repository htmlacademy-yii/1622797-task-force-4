<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $url
 *
 * @property TasksFiles[] $tasksFiles
 * @property Users[] $users
 */
class Files extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['url'], 'required'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
        ];
    }

    /**
     * Gets query for [[TasksFiles]].
     *
     * @return ActiveQuery
     */
    public function getTasksFiles(): ActiveQuery
    {
        return $this->hasMany(TasksFiles::class, ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(Users::class, ['avatar_file_id' => 'id']);
    }
}
