<?php

namespace app\models\forms;

use app\models\Tasks;
use yii\base\Model;

class FeedbackForm extends Model
{
    public $content;
    public $grade;
    public $taskId;

    public function rules(): array
    {
        return [
            [['taskId', 'grade', 'content'], 'required'],
            [['taskId'], 'exist', 'targetClass' => Tasks::class,
                'targetAttribute' => ['taskId' => 'id']],
            [['grade'], 'compare', 'compareValue' => 0, 'operator' => '>',
                'type' => 'number'],
            [['grade'], 'compare', 'compareValue' => 5, 'operator' => '<=',
                'type' => 'number']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'content' => 'Ваш комментарий',
            'grade' => 'Оценка работы'
        ];
    }
}
