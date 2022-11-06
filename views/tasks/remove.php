<?php

use yii\helpers\Url;

?>

<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить данное задание.<br>
            Это действие уберет ваш заказ из ленты опубликованных заданий и вы не сможете найти исполнителя.
        </p>
        <a href="<?= Url::toRoute(['tasks/remove', 'id' => $task->id])?>"
           class="button button--pop-up button--orange">Удалить задание</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
