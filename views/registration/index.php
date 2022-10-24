<?php

/** @var yii\web\View $this
 * @var RegistrationForm $registrationForm
 */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\forms\RegistrationForm;
use app\models\Cities;

$this->title = 'Регистрация нового пользователя';
$cityItems = ArrayHelper::map(Cities::find()->all(), 'id', 'name');
?>

<div class="center-block">
    <div class="registration-form regular-form">

        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
            'method' => 'post'
            ]); ?>
            <?= Html::tag(
                'h4',
                'Регистрация нового пользователя',
                ['class' => 'head-main head-task']
            ); ?>
            <?= $form->field($registrationForm, 'name', [
                'options' => ['class' => 'form-group']]); ?>

            <div class="half-wrapper">
                <?= $form->field($registrationForm, 'email', [
                    'options' => ['class' => ' form-group']
                ]); ?>
                <?= $form->field($registrationForm, 'city', [
                    'labelOptions' => ['for' => 'town-user',
                        'class' => 'form-group control-label'],
                    'inputOptions' => ['id' => 'town-user']])
                    ->dropDownList($cityItems, []); ?>
            </div>

            <div class="half-wrapper">
                <?= $form->field($registrationForm, 'password', [
                'options' => ['class' => 'form-group control-label']])
                ->passwordInput(); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($registrationForm, 'repeatPassword', [
                'options' => ['class' => 'form-group control-label']])
                ->passwordInput(); ?>
            </div>

            <?= $form->field($registrationForm, 'isExecutor', [
                'options' => ['class' => 'form-group']])
                ->checkbox(['class' => 'control-label checkbox-label']); ?>

            <?= Html::SubmitInput('Создать аккаунт', [
                'class' => 'button button--blue']); ?>

        <?php ActiveForm::end() ?>
    </div>
</div>
