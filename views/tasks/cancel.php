<?php

use yii\helpers\Url;

?>

<section class="pop-up pop-up--refusal pop-up--close">
    <?php if ($task->executor_id === Yii::$app->user->identity->id) : ?>
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a href="<?= Url::toRoute(['tasks/cancel', 'id' => $task->id])?>"
           class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
    <?php elseif ($task->customer_id === Yii::$app->user->identity->id) : ?>
        <div class="pop-up--wrapper">
            <h4>Отмена задания</h4>
            <p class="pop-up-text">
                <b>Внимание!</b><br>
                Вы собираетесь отменить это задание.<br>
                Это действие удалит задание из ленты Новых заданий и вы не сможете найти исполнителя.
            </p>
            <a href="<?= Url::toRoute(['tasks/remove', 'id' => $task->id])?>"
               class="button button--pop-up button--orange">Отменить</a>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    <?php endif; ?>
</section>
