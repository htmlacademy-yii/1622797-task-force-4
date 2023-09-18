<?php

/** @var yii\web\View $this
 * @var object $taskCreateForm
 */

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Публикация нового задания';
$categoryItems = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
?>

<div class="main-content main-content--center container">
    <div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin([
        'id' => 'task-create-form',
        'method' => 'post',
        'options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= Html::tag('h3', 'Публикация нового задания', [
            'class' => 'head-main head-main']); ?>
        <div class="form-group">
            <?= $form->field($taskCreateForm, 'taskName', [
                'labelOptions' => ['for' => 'essence-work',
                    'class' => 'control-label'],
                'inputOptions' => ['id' => 'essence-work']]); ?>
        </div>
        <div class="form-group">
            <?= $form->field($taskCreateForm, 'taskDescriptions', [
                'labelOptions' => ['for' => 'username',
                    'class' => 'control-label'],
                'inputOptions' => ['id' => 'username']])->textarea(); ?>
        </div>
        <div class="form-group">
            <?= $form->field($taskCreateForm, 'category', [
                'labelOptions' => ['for' => 'town-user',
                    'class' => 'control-label'],
                'inputOptions' => ['id' => 'town-user']])
                ->dropDownList($categoryItems); ?>
        </div>
        <div class="form-group">
            <?= $form->field($taskCreateForm, 'location', [
                'labelOptions' => ['for' => 'location',
                    'class' => 'control-label'],
                'inputOptions' => ['id' => 'location']])
                ->textInput(['class' => 'location-icon']); ?>
            <?= $form->field($taskCreateForm, 'latitude',
                ['template' => '{input}'])->hiddenInput(); ?>
            <?= $form->field($taskCreateForm, 'longitude',
                ['template' => '{input}'])->hiddenInput(); ?>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($taskCreateForm, 'budget', [
                    'labelOptions' => ['for' => 'budget',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'budget']])
                    ->input('budget', ['class' => 'budget-icon']); ?>
            </div>
            <div class="form-group">
                <?= $form->field($taskCreateForm, 'periodExecution', [
                    'labelOptions' => ['for' => 'period-execution',
                        'class' => 'control-label'],
                    'inputOptions' => ['id' => 'period-execution']])
                    ->input('date'); ?>
            </div>
        </div>
        <p class="form-label">Файлы</p>
        <?= $form->field($taskCreateForm, 'taskFiles[]', [
            'template' => "<label class=\"new-file\" >Добавить новый файл{input}",
            'inputOptions' => ['style' => 'display: none']])
            ->fileInput(['multiple' => true]); ?>

        <?= Html::SubmitInput('Опубликовать', ['class' => 'button button--blue']); ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>
