<?php

/** @var yii\web\View $this
 * @var object $dataProvider
 * @var object $taskFilterForm
 */

use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Categories;
use app\models\forms\TaskFilterForm;

$this->title = 'Новые задания';
$categoryItems = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <div class="pagination-wrapper">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_tasks',
            'summary' => '',
            'pager' => [
                'prevPageLabel' => '',
                'nextPageLabel' => '',
                'maxButtonCount' => 3,
                'options' => [
                    'tag' => 'ul',
                    'class' => 'pagination-list'
                ],
                 'linkOptions' => ['class' => 'link link--page'],
                 'activePageCssClass' => 'pagination-item pagination-item--active',
                 'pageCssClass' => 'pagination-item',
                 'prevPageCssClass' => 'pagination-item mark',
                 'nextPageCssClass' => 'pagination-item mark',
             ],
        ]);
?>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "{input}",
                ],
            ]); ?>
            <?= Html::tag('h4', 'Категории', ['class' => 'head-card']); ?>
            <?= $form->field($taskFilterForm, 'category')
            ->checkboxList(
                $categoryItems,
                [
                    'class' => 'checkbox-wrapper',
                    'itemOptions' => [
                        'labelOptions' => ['class' => 'control-label']
                    ]
                ]
            ); ?>

            <?= Html::tag('h4', 'Дополнительно', ['class' => 'head-card']); ?>
            <?= $form->field($taskFilterForm, 'remoteTask')
                ->checkbox(
                    [
                        'id' => 'remoteTask',
                        'labelOptions' => ['class' => 'control-label']
                    ]
                ); ?>
            <?= $form->field($taskFilterForm, 'withoutExecutor')
                ->checkbox(
                    [
                        'id' => 'withoutExecutor',
                        'labelOptions' => ['class' => 'control-label']
                    ]
                ); ?>

            <?= Html::tag('h4', 'Период', ['class' => 'head-card']); ?>
            <?= $form->field($taskFilterForm, 'period', [
                'template' => "{label}\n{input}",
                'labelOptions' => [
                    'for' => 'period-value'],
                'inputOptions' => ['id' => 'period-value']

            ])
                ->dropDownList(TaskFilterForm::getPeriodValue(), [
                ])->label(false); ?>

                <?= Html::SubmitInput('Искать', ['class' => 'button button--blue'])?>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
