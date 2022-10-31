<?php

/** @var yii\web\View $this
 * @var object $task
 * @var object $taskCreateForm
 */

use app\widgets\StarsWidget;
use taskforce\helpers\MainHelpers;
use yii\helpers\Url;

?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= $task->name; ?></h3>
        <p class="price price--big"><?= $task->budget; ?> ₽</p>
    </div>
    <p class="task-description"><?= $task->description; ?></p>
    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
    <div class="task-map">
        <img class="map" src="../../img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town">Москва</p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($task->responses as $response) : ?>
        <div class="response-card">
            <img class="customer-photo" src="<?=
            (empty($response->executor->avatarFile->url)) ? '' : $response->executor->avatarFile->url; ?>"
                 width="146" height="156" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <a href="<?= Url::toRoute(['user/view','id' => $response->executor->id]); ?>"
                   class="link link--block link--big"><?= $response->executor->name; ?></a>
                <div class="response-wrapper">
                    <div class="stars-rating small">
                        <?= StarsWidget::widget(['grade' => $response->executor->getExecutorGrade()]); ?>
                    </div>
                    <p class="reviews"><?= $response->executor->getFeedbacksCount(); ?>
                        <?= MainHelpers::getNounPluralForm(
                            $response->executor->getFeedbacksCount(),
                            'отзыв',
                            'отзыва',
                            'отзывов'
                        ); ?></p>
                </div>
                <p class="response-message"><?= $response->comment; ?></p>
            </div>
            <div class="feedback-wrapper">
                <p class="info-text"><span class="current-time">
                        <?= Yii::$app->formatter->format(
                            $response->date_creation,
                            'relativeTime'
                        ); ?></span></p>
                <p class="price price--small"><?= $response->price; ?> ₽</p>
            </div>
            <div class="button-popup">
                <a href="#" class="button button--blue button--small">Принять</a>
                <a href="#" class="button button--orange button--small">Отказать</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= $task->category->name; ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->format(
                $task->date_creation,
                'relativeTime'
            ); ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?=Yii::$app->formatter->asDate(
                $task->period_execution,
                'php:d F Y'
            ); ?></dd>
            <dt>Статус</dt>
            <dd><?= $task->getStatusName(); ?></dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <?php foreach ($task->tasksFiles as $taskFile) : ?>
            <li class="enumeration-item">
                <a href="<?= $taskFile->file->url; ?>"
                class="link link--block link--clip"><?= $taskFile->file->url; ?>
                </a>
                <p class="file-size"><?= Yii::$app->formatter->asShortSize(
                    filesize(Yii::getAlias(
                        '@webroot/uploads/'
                    ) . $taskFile->file->url)
                                     ); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
