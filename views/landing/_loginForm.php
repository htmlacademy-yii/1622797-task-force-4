<?php

/** @var LoginForm $loginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\forms\LoginForm;

?>

<section class="modal enter-form form-modal" id="enter-form">
        <?= Html::tag('h2', 'Вход на сайт'); ?>
        <?php $form = ActiveForm::begin(['id' => 'login-form',
            'enableAjaxValidation' => true,
            'method' => 'post']); ?>

            <?= $form->field($loginForm, 'email')
                ->input('email', ['class' => 'input input-middle']); ?>
            <?= $form->field($loginForm, 'password')
                ->passwordInput(['class' => 'input input-middle']); ?>

            <?= Html::SubmitInput('Войти', ['class' => 'button']); ?>

        <?php ActiveForm::end(); ?>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
