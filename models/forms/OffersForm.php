<?php

namespace app\models\forms;

use app\models\Tasks;
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
}
