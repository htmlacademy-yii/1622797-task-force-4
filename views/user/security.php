<?php

/** @var yii\web\View $this
 * @var object $securityForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<main class="main-content main-content--left container">

    <?= $this->render('menu'); ?>

    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'settings-form'
        ]); ?>
        <h3 class="head-main head-regular">Смена пароля</h3>

        <?= $form->field($securityForm, 'currentPassword', [
                'options' => ['class' => 'form-group control-label']])->passwordInput(); ?>
        <?= $form->field($securityForm, 'newPassword', [
                'options' => ['class' => 'form-group control-label']])->passwordInput(); ?>
        <?= $form->field($securityForm, 'repeatNewPassword', [
                'options' => ['class' => 'form-group control-label']])->passwordInput(); ?>

        <?= Html::SubmitInput('Сохранить', ['class' => 'button button--blue']); ?>

        <?php ActiveForm::end(); ?>

    </div>
</main>
