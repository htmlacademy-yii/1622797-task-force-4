<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\MainAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

MainAsset::register($this);
$user = Yii::$app->user->getIdentity();
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>

<body>
<?php $this->beginBody(); ?>

<header class="page-header">
    <nav class="main-nav">
        <a href="<?= Url::toRoute('/tasks'); ?>" class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <li class="list-item list-item--active">
                    <a class="link link--nav">Новое</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav" >Мои задания</a>
                </li>
                <?php if ($user->is_executor !== 1) : ?>
                <li class="list-item">
                    <a href="<?= Url::toRoute('/tasks/create'); ?>" class="link link--nav" >Создать задание</a>
                </li>
                <?php endif; ?>
                <li class="list-item">
                    <a href="#" class="link link--nav" >Настройки</a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
    <?php if (!Yii::$app->user->isGuest) : ?>
    <div class="user-block">
        <a href="#">
            <img class="user-photo" src="#" width="55" height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name"><?= (Yii::$app->user->getIdentity()->name); ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="#" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::toRoute('/landing/logout') ;?>"
                           class="link">Выход из системы</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>

<main class="main-content container">

    <?= $content; ?>

</main>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
