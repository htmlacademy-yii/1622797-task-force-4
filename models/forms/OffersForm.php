<?php

namespace app\models\forms;

use app\models\Offers;
use app\models\Tasks;
use Yii;
use yii\base\Model;

class OffersForm extends Model
{
    public $content;
    public $price;
    public $taskId;

    public function rules(): array
    {
        return [
            [['taskId', 'price', 'content'], 'required'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>',
                'type' => 'number'],
            [['taskId'], 'exist', 'targetClass' => Tasks::class,
                'targetAttribute' => ['taskId' => 'id']]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'content' => 'Ваш комментарий',
            'price' => 'Стоимость'
        ];
    }

    /** Метод по публикации отзыва к Заданию
     *
     * @return void
     */
    public function createOffers(): void
    {
        $offers = new Offers();
        $offers->task_id = $this->taskId;
        $offers->comment = $this->content;
        $offers->price = $this->price;
        $offers->executor_id = Yii::$app->user->identity->id;
        $offers->refuse = 0;
        $offers->save();
    }
}
