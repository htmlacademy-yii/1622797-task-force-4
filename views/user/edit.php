<?php

/** @var yii\web\View $this
 * @var object $editProfileForm
 */

use app\models\Categories;
use app\models\Users;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Мой профиль';
$categoryItems = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
$user = Users::findOne(Yii::$app->user->getId());
$defaultAvatar = '/img/avatars/default-avatar.png';
?>

<main class="main-content main-content--left container">

    <?= $this->render('menu'); ?>

    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to('user/edit')]); ?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <img class="avatar-preview" src="<?= Html::encode(empty($user->avatar)) ?
                        $defaultAvatar : $user->avatar; ?>" width="83" height="83">
                </div>
                <?= $form->field($editProfileForm, 'avatar', [
                    'template' => "<label class=\"button button--black\">Сменить аватар{input}",
                    'inputOptions' => ['style' => 'display: none', 'hidden' => true],
                ])->fileInput(); ?>
            </div>
                <?= $form->field($editProfileForm, 'name', [
                    'labelOptions' => ['for' => 'profile-name',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'profile-name']]); ?>
            <div class="half-wrapper">
                    <?= $form->field($editProfileForm, 'email', [
                        'labelOptions' => ['for' => 'profile-email',
                            'class' => 'control-label']]); ?>
                    <?= $form->field($editProfileForm, 'birthday', [
                        'labelOptions' => ['for' => 'profile-date',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-date']])->input('date'); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($editProfileForm, 'phone', [
                        'labelOptions' => ['for' => 'profile-phone',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-phone']]); ?>
                <?= $form->field($editProfileForm, 'telegram', [
                        'labelOptions' => ['for' => 'profile-tg',
                            'class' => 'control-label'],
                        'inputOptions' => ['id' => 'profile-tg']]); ?>
            </div>
                <?= $form->field($editProfileForm, 'bio', [
                    'labelOptions' => ['for' => 'profile-info',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'profile-info']]); ?>
        <?php if ($user->is_executor === 1) : ?>
                <?= $form->field($editProfileForm, 'category')->checkboxList(
                    $categoryItems,
                    ['class' => 'form-group checkbox-profile control-label']
                ) ?>
                <?= $form->field(
                    $editProfileForm,
                    'showContacts',
                    [
                        'options' => ['class' => 'form-group']]
                )
                ->checkbox(['class' => 'control-label checkbox-label']); ?>
        <?php endif; ?>

        <?= Html::SubmitInput('Сохранить', ['class' => 'button button--blue']); ?>

        <?php ActiveForm::end(); ?>
    </div>
</main>
