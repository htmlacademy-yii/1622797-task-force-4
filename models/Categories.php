<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string|null $icon
 *
 * @property ExecutorCategory[] $executorCategories
 * @property Tasks[] $tasks
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128],
            [['icon'], 'string', 'max' => 20],
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
            'icon' => 'Icon',
        ];
    }

    /**
     * Gets query for [[ExecutorCategories]].
     *
     * @return ActiveQuery
     */
    public function getExecutorCategories(): ActiveQuery
    {
        return $this->hasMany(ExecutorCategory::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Tasks::class, ['category_id' => 'id']);
    }
}
