<?php

/** @var yii\web\View $this
 * @var object $user
 */

use app\models\Tasks;
use app\widgets\StarsWidget;
use taskforce\helpers\MainHelpers;
use yii\helpers\Html;
use yii\helpers\Url;

$defaultAvatar = '/img/avatars/default-avatar.png';
?>

<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name); ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?= Html::encode(empty($user->avatar)) ?
                $defaultAvatar : $user->avatar; ?>"
                 width="191" height="190" alt="Фото пользователя">
            <div class="card-rate">
                <div class="stars-rating big"><?= StarsWidget::widget(
                    ['grade' => $user->getExecutorGrade()]
                ); ?></div>
                <span class="current-rate"><?= Html::encode($user->getExecutorGrade()); ?></span>
            </div>
        </div>
        <p class="user-description"><?= Html::encode($user->bio); ?></p>
    </div>
    <div class="specialization-bio">
        <?php if (!empty($user->executorCategories)) : ?>
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($user->executorCategories as $executorCategory) : ?>
                <li class="special-item">
                    <a href="#"
                       class="link link--regular">
                        <?= Html::encode($executorCategory->category->name); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>,
                <span class="town-info"><?= Html::encode(!empty($user->city) ?
                                    $user->city->name : '' ); ?></span>
                <?php if ($user->birthday !== null) : ?>
                <span class="age-info">,<?= Html::encode($user->getUserAge()); ?></span>
                    <?= MainHelpers::getNounPluralForm(
                                        $user->getUserAge(),
                                        'год',
                                        'года',
                                        'лет'
                                    ); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php if (!empty($user->executorFeedbacks)) : ?>
    <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->executorFeedbacks as $executorFeedback) : ?>
    <div class="response-card">
        <img class="customer-photo" src="
            <?= Html::encode(empty(
                $executorFeedback->task->customer->avatar) ? $defaultAvatar :
            $executorFeedback->task->customer->avatar); ?>"
             width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«<?= Html::encode($executorFeedback->description); ?>»</p>
            <p class="task">Задание «<a href="
            <?= Url::to(['tasks/view','id' => $executorFeedback->task->id]); ?>"
                                        class="link link--small">
                    <?= Html::encode($executorFeedback->task->name); ?></a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><?= StarsWidget::widget([
                    'grade' => $executorFeedback->grade]); ?></div>
            <p class="info-text"><span class="current-time">
                    <?= Yii::$app->formatter->format(
                        Html::encode($executorFeedback->date_creation),
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
            <dd><?= Html::encode($user->getExecutedTasks()->count())  . ' выполнено, ' .
                Html::encode($user->getFailedTasks()->count()) . ' провалено ' ; ?></dd>
            <dt>Место в рейтинге</dt>
            <dd><?= Html::encode($user->getExecutorRating()); ?> место</dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asDate(Html::encode($user->date_creation)); ?></dd>
            <dt>Статус</dt>
            <dd><?= Html::encode($user->getExecutorStatus()); ?></dd>
        </dl>
    </div>
    <?php if (
        $user->show_contacts === 1 || Tasks::find()
                ->where(['customer_id' => $user->id])
                ->andWhere(['executor_id' => Yii::$app->user->id])
    ) : ?>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
                    <?php if ($user->phone !== null) : ?>
            <li class="enumeration-item">
                <a href="tel:<?= $user->phone; ?>"
                   class="link link--block link--phone">
                        <?= Html::encode($user->phone); ?>
                </a>
            </li>
                    <?php endif; ?>
            <li class="enumeration-item">
                <a href="mailto:<?= $user->email; ?>"
                   class="link link--block link--email">
                    <?= Html::encode($user->email); ?>
                </a>
            </li>
                    <?php if ($user->telegram !== null) : ?>
            <li class="enumeration-item">
                <a href="https://t.me/<?= substr($user->telegram, 1) ?>"
                   class="link link--block link--tg">
                        <?= Html::encode($user->telegram); ?>
                </a>
            </li>
                    <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
