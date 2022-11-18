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
use app\widgets\ActionsWidget;

$defaultAvatar = '/img/avatars/default-avatar.png';
?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name); ?></h3>
        <?php if ($task->budget !== null) : ?>
        <p class="price price--big"><?= Html::encode($task->budget) . ' ₽'; ?></p>
        <?php endif; ?>
    </div>
    <p class="task-description"><?= Html::encode($task->description); ?></p>
        <?php foreach (
            $task->getAvailableActions(Yii::$app->user->identity->id)
            as $actionObject
        ) : ?>
                                    <?= $actionObject !== null ? ActionsWidget::widget([
                                            'actionObject' => $actionObject]) : '' ; ?>
        <?php endforeach; ?>
    <?php if ($task->latitude && $task->longitude && $task->address) : ?>
    <div class="task-map">
        <div class="map" style="width: 725px; height: 346px" id="map"></div>
            <script type="text/javascript">
                ymaps.ready(init);
                function init(){
                    var myMap = new ymaps.Map("map", {
                        center: [<?= Html::encode($task->latitude); ?>,
                            <?= Html::encode($task->longitude); ?>],
                        zoom: 14
                    });
                }
            </script>
        <input type="hidden" id="latitude" value="<?= Html::encode($task->latitude); ?>">
        <input type="hidden" id="longitude" value="<?= Html::encode($task->longitude); ?>">
        <p class="map-address town"></p>
        <p class="map-address"><?= Html::encode($task->address); ?></p>
    </div>
    <?php endif; ?>
    <?php if ($user->id === $task->customer_id || $user->is_executor === 1) : ?>
    <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($task->offers as $response) : ?>
            <?php if (
                $user->id === $task->customer_id ||
                    $user->id === $response->executor_id
            ) : ?>
        <div class="response-card">
            <img class="customer-photo" src="<?=
            Html::encode(empty($response->executor->avatar)) ?
                            $defaultAvatar : $response->executor->avatar; ?>"
                 width="146" height="156" alt="Фото пользователя">
            <div class="feedback-wrapper">
                <a href="<?= Url::to(['user/view','id' => $response->executor->id]); ?>"
                   class="link link--block link--big"><?= Html::encode(
                                $response->executor->name
                            ); ?></a>
                <div class="response-wrapper">
                    <div class="stars-rating small">
                        <?= StarsWidget::widget([
                            'grade' => $response->executor->getExecutorGrade()]); ?>
                    </div>
                    <p class="reviews"><?= Html::encode(
                                $response->executor->getFeedbacksCount()
                            ); ?>
                        <?= MainHelpers::getNounPluralForm(
                            $response->executor->getFeedbacksCount(),
                            'отзыв',
                            'отзыва',
                            'отзывов'
                        ); ?></p>
                </div>
                <p class="response-message"><?= Html::encode($response->comment); ?></p>
            </div>
            <div class="feedback-wrapper">
                <p class="info-text"><span class="current-time">
                        <?= Yii::$app->formatter->asRelativeTime(
                            Html::encode($response->date_creation)
                        ); ?></span></p>
                <?php if ($response->price !== null) : ?>
                <p class="price price--small"><?= Html::encode(
                    $response->price . ' ₽'); ?></p>
                <?php endif; ?>
            </div>
                <?php if (
                    $user->id === $task->customer_id &&
                        $response->refuse === 0 &&
                    $response->task->status === Tasks::STATUS_NEW
                ) : ?>
            <div class="button-popup">
                <a href="<?= Url::toRoute(['tasks/start',
                                    'taskId' => $response->task_id,
                    'userId' => $response->executor_id]) ?>"
                   class="button button--blue button--small">Принять</a>
                <a href="<?= Url::toRoute(['tasks/refuse',
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
            <dd><?= Html::encode($task->category->name); ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime(Html::encode($task->date_creation)); ?></dd>
            <?php if ($task->period_execution) : ?>
            <dt>Срок выполнения</dt>
            <dd><?=Yii::$app->formatter->asDate(Html::encode($task->period_execution)); ?></dd>
            <?php endif; ?>
            <dt>Статус</dt>
            <dd><?= Html::encode($task->getStatusName()); ?></dd>
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
                    ) . Html::encode($taskFile->file->url))
                ); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php echo $this->render('offers', ['task' => $task,
        'newOffers' => $newOffers]); ?>
    <?php echo $this->render('feedback', ['task' => $task,
        'feedbackForm' => $feedbackForm]); ?>
    <?php echo $this->render('cancel', ['task' => $task]); ?>
</div>
