<?php

namespace app\models\forms;

use app\models\Feedback;
use app\models\Tasks;
use Yii;
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

    public function createFeedback()
    {
        $feedback = new Feedback();
        $task = Tasks::findOne(['id' => $this->taskId]);
        $task->status = Tasks::STATUS_DONE;
        $feedback->customer_id = Yii::$app->user->identity->id;
        $feedback->executor_id = $task->executor_id;
        $feedback->task_id = $this->taskId;
        $feedback->description = $this->content;
        $feedback->grade = $this->grade;
        $feedback->save();
    }
}
