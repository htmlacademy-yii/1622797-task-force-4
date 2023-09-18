<?php

/** @var yii\web\View $this
 * @var object $model
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="task-card">
    <div class="header-task">
        <a  href="<?= Url::to(['tasks/view/', 'id' => $model->id]); ?>"
            class="link link--block link--big"><?= Html::encode($model->name); ?></a>
        <p class="price price--task"><?= $model->budget ? Html::encode($model->budget) . ' ₽' : 'Договорная'; ?></p>
    </div>
    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter
                ->format(
                    Html::encode($model->date_creation),
                    'relativeTime'
                ) ?></span></p>
    <p class="task-text"><?= Html::encode($model->description); ?></p>
    <div class="footer-task">
        <?php if (isset($model->city->name)) : ?>
        <p class="info-text town-text"><?= Html::encode($model->city->name); ?></p>
        <?php endif; ?>
        <p class="info-text category-text"><?= Html::encode($model->category->name); ?></p>
        <a href="<?= Url::to(['tasks/view/', 'id' => $model->id]); ?>"
           class="button button--black">Смотреть Задание</a>
    </div>
</div>
