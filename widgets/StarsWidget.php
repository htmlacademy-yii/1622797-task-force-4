<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class StarsWidget extends Widget
{
    public int $grade;
    private const MAX_COUNT_STARS = 5;

    public function run(): string
    {
        $result = '';
        for ($i = 0; $i < self::MAX_COUNT_STARS; $i++) {
            $result .= Html::tag('span', '&nbsp;', [
                'class' => $this->grade > $i ? 'fill-star' : ''
            ]);
        }
        return $result;
    }
}
