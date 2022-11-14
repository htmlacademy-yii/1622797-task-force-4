<?php

/** @var yii\web\View $this
 * @var object $task
 * @var object $taskCreateForm
 * @var object $user
 * @var object $newOffers
 * @var object $feedbackForm
 */

use app\models\Tasks;
use app\widgets\StarsWidget;
use taskforce\helpers\MainHelpers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use app\widgets\ActionsWidget;

$defaultAvatar = '/img/avatars/default-avatar.png';
?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= HtmlPurifier::process($task->name); ?></h3>
        <p class="price price--big"><?= HtmlPurifier::process($task->budget); ?> ₽</p>
    </div>
    <p class="task-description"><?= HtmlPurifier::process($task->description); ?></p>
    <?php if (!$task->checkUserOffers(Yii::$app->user->identity->id)) : ?>
        <?php foreach ($task->getAvailableActions(Yii::$app->user->identity->id) as $actionObject) : ?>
            <?= $actionObject !== null ? ActionsWidget::widget(['actionObject' => $actionObject]) : '' ; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($task->latitude && $task->longitude) : ?>
    <div class="task-map">
        <div class="map" style="width: 725px; height: 346px" id="map"></div>
            <script type="text/javascript">
                ymaps.ready(init);
                function init(){
                    var myMap = new ymaps.Map("map", {
                        center: [<?= $task->latitude; ?>, <?= $task->longitude; ?>],
                        zoom: 14
                    });
                }
            </script>
        <input type="hidden" id="latitude" value="<?= HTML::encode($task->latitude); ?>">
        <input type="hidden" id="longitude" value="<?= HTML::encode($task->longitude); ?>">
        <p class="map-address town"><?= $task->city->name; ?></p>
        <p class="map-address"><?= $task->address; ?></p>
    </div>
    <?php endif; ?>
    <?php if ($user->id === $task->customer_id || $user->is_executor === 1) : ?>
    <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($task->offers as $response) : ?>
            <?php if ($user->id === $task->customer_id || $user->id === $response->executor_id) : ?>
        <div class="response-card">
            <img class="customer-photo" src="<?=
            (empty($response->executor->avatarFile->url)) ?
                $defaultAvatar : $response->executor->avatarFile->url; ?>"
                 width="146" height="156" alt="Фото пользователя">
            <div class="feedback-wrapper">
                <a href="<?= Url::toRoute(['user/view','id' => $response->executor->id]); ?>"
                   class="link link--block link--big"><?= HtmlPurifier::process($response->executor->name); ?></a>
                <div class="response-wrapper">
                    <div class="stars-rating small">
                        <?= StarsWidget::widget(['grade' => $response->executor->getExecutorGrade()]); ?>
                    </div>
                    <p class="reviews"><?= HtmlPurifier::process($response->executor->getFeedbacksCount()); ?>
                        <?= MainHelpers::getNounPluralForm(
                $response->executor->getFeedbacksCount(),
                'отзыв',
                'отзыва',
                'отзывов'
            ); ?></p>
                </div>
                <p class="response-message"><?= HtmlPurifier::process($response->comment); ?></p>
            </div>
            <div class="feedback-wrapper">
                <p class="info-text"><span class="current-time">
                        <?= Yii::$app->formatter->asRelativeTime(
                            HtmlPurifier::process($response->date_creation)
                        ); ?></span></p>
                <?php if ($response->price !== null) : ?>
                <p class="price price--small"><?= HtmlPurifier::process($response->price . ' ₽'); ?></p>
                <?php endif; ?>
            </div>
                <?php if (
                    $user->id === $task->customer_id &&
                        $response->refuse === 0 &&
                    $response->task->status === Tasks::STATUS_NEW
                ) : ?>
            <div class="button-popup">
                <a href="<?= Url::toRoute(['/tasks/start',
                    'taskId' => $response->task_id, 'userId' => $response->executor_id]) ?>"
                   class="button button--blue button--small">Принять</a>
                <a href="<?= Url::toRoute(['/tasks/refuse',
                    'responseId' => $response->id]) ?>"
                   class="button button--orange button--small">Отказать</a>
            </div>
                <?php endif; ?>
        </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= HtmlPurifier::process($task->category->name); ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime(HtmlPurifier::process($task->date_creation)); ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?=Yii::$app->formatter->asDate(HtmlPurifier::process($task->period_execution)); ?></dd>
            <dt>Статус</dt>
            <dd><?= HtmlPurifier::process($task->getStatusName()); ?></dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <?php foreach ($task->tasksFiles as $taskFile) : ?>
            <li class="enumeration-item">
                <?= Html::a(
                    $taskFile->file->url,
                    ['tasks/download', 'path' => $taskFile->file->url],
                    ['class' => 'link link--block link--clip']
                ); ?>
                <p class="file-size"><?= Yii::$app->formatter->asShortSize(
                    filesize(Yii::getAlias(
                        '@webroot/uploads/'
                    ) . HtmlPurifier::process($taskFile->file->url))
                ); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php echo $this->render('offers', ['task' => $task, 'newOffers' => $newOffers]); ?>
    <?php echo $this->render('feedback', ['task' => $task, 'feedbackForm' => $feedbackForm]); ?>
    <?php echo $this->render('cancel', ['task' => $task]); ?>
</div>
