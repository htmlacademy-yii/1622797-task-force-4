<?php

/** @var yii\web\View $this
 * @var object $user
 */

use app\widgets\StarsWidget;
use taskforce\helpers\MainHelpers;
use yii\helpers\Url;

?>

<div class="left-column">
    <h3 class="head-main"><?= $user->name; ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?= (empty(
                $user->avatarFile->url
            )) ? '' : $user->avatarFile->url; ?>"
                 width="191" height="190" alt="Фото пользователя">
            <div class="card-rate">
                <div class="stars-rating big"><?= StarsWidget::widget(
                ['grade' => $user->getExecutorGrade()]
            ); ?></div>
                <span class="current-rate"><?= $user->getExecutorGrade(); ?></span>
            </div>
        </div>
        <p class="user-description"><?= $user->bio; ?></p>
    </div>
    <div class="specialization-bio">
        <?php if (!empty($user->executorCategories)) : ?>
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($user->executorCategories as $executorCategory) : ?>
                <li class="special-item">
                    <a href="#" class="link link--regular">
                        <?= $executorCategory->category->name; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>,
                <span class="town-info"><?= !empty($user->city) ?
                                    $user->city->name . ',' : '' ; ?></span>
                <span class="age-info"><?= $user->getUserAge(); ?></span>
                <?= MainHelpers::getNounPluralForm(
                                        $user->getUserAge(),
                                        'год',
                                        'года',
                                        'лет'
                                    ); ?>
            </p>
        </div>
    </div>
    <?php if (!empty($user->executorFeedbacks)) : ?>
    <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->executorFeedbacks as $executorFeedback) : ?>
    <div class="response-card">
        <img class="customer-photo" src="
            <?= $executorFeedback->task->customer->avatarFile->url; ?>"
             width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«<?= $executorFeedback->description; ?>»</p>
            <p class="task">Задание «<a href="
            <?= Url::toRoute(['tasks/view','id' => $executorFeedback->task->id]); ?>"
                                        class="link link--small">
                    <?= $executorFeedback->task->name; ?></a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><?= StarsWidget::widget([
                    'grade' => $executorFeedback->grade]); ?></div>
            <p class="info-text"><span class="current-time">
                    <?= Yii::$app->formatter->format(
                        $executorFeedback->date_creation,
                        'relativeTime'
                    ); ?></span></p>
        </div>
    </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?= $user->getExecutedTasks()->count()  . ' выполнено, ' .
                        $user->getFailedTasks()->count() . ' провалено ' ; ?></dd>
            <dt>Место в рейтинге</dt>
            <dd><?= $user->getExecutorRating(); ?> место</dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asDate($user->date_creation); ?></dd>
            <dt>Статус</dt>
            <dd><?= $user->getExecutorStatus(); ?></dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone"><?= $user->phone ?: 'Не указан' ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email"><?= $user->email; ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg"><?= $user->telegram ?: 'Не указан' ?></a>
            </li>
        </ul>
    </div>
</div>
