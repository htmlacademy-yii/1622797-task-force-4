<?php

/** @var yii\web\View $this
 * @var object $feedbackForm
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile('/js/starsGrade.js');

?>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <?= Html::tag('h4', 'Завершение задания'); ?>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'feedback-form',
                'action' => Url::toRoute('tasks/feedback')]); ?>
                <div class="form-group">
                    <?= $form->field($feedbackForm, 'content', [
                        'labelOptions' => ['for' => 'completion-comment',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'completion-comment']])->textarea(); ?>
                </div>
                <?= $form->field($feedbackForm, 'grade', [
                    'template' => '{input}'])->hiddenInput()->label(false); ?>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>

            <?= $form->field($feedbackForm, 'taskId', [
                'template' => '{input}'])
                ->hiddenInput(['value' => $task->id])->label(false); ?>)

                <?= Html::submitInput(
                    'Завершить',
                    ['class="button button--pop-up button--blue']
                ); ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
