<?php

use yii\widgets\Menu;

?>

<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?= Menu::widget(['items' => [
        ['label' => 'Мой профиль', 'url' => ['user/edit']],
        ['label' => 'Безопасность', 'url' => ['user/security']]],
        'options' => [
            'class' => 'side-menu-list',
        ],
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'activeCssClass' => 'side-menu-item--active',
        'itemOptions' => ['class' => 'side-menu-item'],
    ]);?>
</div>
