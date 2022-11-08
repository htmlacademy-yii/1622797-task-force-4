<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class ActionsWidget extends Widget
{
    public $actionObject;

    public function run()
    {
        return Html::a(
            HtmlPurifier::process($this->actionObject->name),
            $this->actionObject->getLink(),
            ['class' => $this->actionObject->class,
                'data-action' => $this->actionObject->dataAction]
        );
    }
}
