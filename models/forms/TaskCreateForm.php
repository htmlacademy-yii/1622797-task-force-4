<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Categories;

class TaskCreateForm extends Model
{
    public $taskName;
    public $taskDescriptions;
    public $category;
    public $location;
    public $budget;
    public $periodExecution;
    public $taskFiles;

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'taskName' => 'Опишите суть работы',
            'taskDescriptions' => 'Подробности задания',
            'category' => 'Категория',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'periodExecution' => 'Срок исполнения',
            'taskFiles' => 'Файлы'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['taskName', 'taskDescriptions', 'category'], 'required'],
            ['taskName', 'string', 'min' => 3, 'max' => 255],
            ['taskDescriptions', 'string', 'min' => 30],
            [['periodExecution'], 'compare', 'compareValue' => date('Y-m-d'),
                'operator' => '>', 'type' => 'date',
                'message' => 'Дата выполнения задания не может быть раньше текущей даты'],
            ['category', 'exist', 'targetClass' => Categories::class,
                'targetAttribute' => ['category' => 'id']],
            ['budget', 'integer', 'min' => 1],
            [['taskFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0],
        ];
    }
}
