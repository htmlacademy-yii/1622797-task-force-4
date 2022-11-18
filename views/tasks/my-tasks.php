<?php

/** @var yii\web\View $this
 * @var object $dataProvider
 * @var string $status
 */

use app\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Menu;

$this->title = 'Мои Задания';
$executor = Yii::$app->user->identity->is_executor === 1;
?>

<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?= Menu::widget([
        'items' => [
            ['label' => 'Новые', 'url' => ['tasks/my-tasks',
                'status' => Tasks::STATUS_NEW], 'visible' => !$executor],
            ['label' => 'В процессе', 'url' => ['tasks/my-tasks',
                'status' => Tasks::STATUS_AT_WORK]],
            ['label' => 'Просроченные', 'url' => ['tasks/my-tasks',
                'status' => Tasks::STATUS_FAILED], 'visible' => $executor],
            ['label' => 'Закрытые', 'url' => ['tasks/my-tasks',
                'status' => Tasks::STATUS_DONE]],
        ],
        'options' => [
            'class' => 'side-menu-list'
        ],
        'itemOptions' => [
            'class' => 'side-menu-item'
        ],
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'activateItems' => true,
        'activateParents' => true,
        'activeCssClass' => 'side-menu-item--active',
    ]);
    ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular"><?= Html::encode(
    ArrayHelper::getValue(Tasks::getStatusesLabels(), $status)); ?></h3>
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
