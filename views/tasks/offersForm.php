<?php

/** @var yii\web\View $this
 * @var object $offersForm
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <?= Html::tag('h4', 'Добавление отклика к заданию'); ?>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'offers-form',
                'action' => Url::toRoute('tasks/offers')]); ?>
                <div class="form-group">
                    <?= $form->field($offersForm, 'content', [
                    'labelOptions' => ['for' => 'addition-comment',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'addition-comment']])->textarea(); ?>
                </div>
                <div class="form-group">
                    <?= $form->field($offersForm, 'price', [
                        'labelOptions' => ['for' => 'addition-price',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'addition-price']])
                        ->input('number'); ?>
                </div>
                    <?= $form->field($offersForm, 'taskId', [
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
