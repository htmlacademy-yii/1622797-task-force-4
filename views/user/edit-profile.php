<?php

/** @var yii\web\View $this
 * @var object $editProfileForm
 */

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Мой профиль';
$categoryItems = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item side-menu-item--active">
                <a class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item">
                <a href="#" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'editprofile-form',
            'method' => 'post',
            'action' => Url::toRoute('tasks/edit')]); ?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <img class="avatar-preview" src="../img/man-glasses.png" width="83" height="83">
                </div>
            </div>
            <div class="form-group">
                <?= $form->field($editProfileForm, 'name', [
                    'labelOptions' => ['for' => 'profile-name',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'profile-name']]); ?>
            </div>
            <div class="half-wrapper">
                <div class="form-group">
                    <?= $form->field($editProfileForm, 'email', [
                        'labelOptions' => ['for' => 'profile-email',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-email']]); ?>
                </div>
                <div class="form-group">
                    <?= $form->field($editProfileForm, 'birthday', [
                        'labelOptions' => ['for' => 'profile-date',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-date']])->input('date'); ?>
                </div>
            </div>
            <div class="half-wrapper">
                <div class="form-group">
                    <?= $form->field($editProfileForm, 'phone', [
                        'labelOptions' => ['for' => 'profile-phone',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-phone']]); ?>
                </div>
                <div class="form-group">
                    <?= $form->field($editProfileForm, 'telegram', [
                        'labelOptions' => ['for' => 'profile-tg',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-tg']]); ?>
                </div>
            </div>
            <div class="form-group">
                <?= $form->field($editProfileForm, 'bio', [
                    'labelOptions' => ['for' => 'profile-info',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'profile-info']]); ?>
            </div>
            <div class="form-group">
                <p class="form-label">Выбор специализаций</p>
                <div class="checkbox-profile">
                    <?= $form->field($editProfileForm, 'category', [
                        'labelOptions' => ['for' => 'сourier-services',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'сourier-services']])
                        ->dropDownList($categoryItems); ?>
                </div>
            </div>

        <?= Html::SubmitInput('Сохранить', ['class' => 'button button--blue']); ?>

        <?php ActiveForm::end(); ?>
    </div>
</main>
