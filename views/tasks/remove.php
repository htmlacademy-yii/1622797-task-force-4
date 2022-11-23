<?php

use yii\helpers\Html;
use app\models\Tasks;

/**
 * @var Tasks $task
 */

?>

<section class="pop-up pop-up--cancel pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить это задание.<br>
            Это действие удалит задание из ленты заданий и вы не сможете найти исполнителя.
        </p>
        <?= Html::a(
            'Отменить',
            ['tasks/remove', 'id' => $task->id],
            ['class' => 'button button--pop-up button--orange']
); ?>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
