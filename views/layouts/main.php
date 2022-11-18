<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\MainAsset;
use app\models\Tasks;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

MainAsset::register($this);
$user = Yii::$app->user->getIdentity();
$defaultAvatar = '/img/avatars/default-avatar.png';
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
    <script src=
            "https://api-maps.yandex.ru/2.1/?apikey=a3f42aa0-e7e6-4132-bec6-856c58de44df&lang=ru_RU"
            type="text/javascript">
    </script>
</head>

<body>
<?php $this->beginBody(); ?>

<header class="page-header">
    <nav class="main-nav">
        <a href="<?= Url::to('/tasks'); ?>" class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60
                 alt="taskforce">
        </a>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="nav-wrapper">
            <?= Menu::widget([
                'items' => [
                    ['label' => 'Новое', 'url' => ['tasks/index']],
                    ['label' => 'Мои задания', 'url' => ['tasks/my-tasks',
                        'status' => Tasks::STATUS_NEW],
                        'visible' => !Yii::$app->user->identity->is_executor == 1],
                    ['label' => 'Мои задания', 'url' => ['tasks/my-tasks',
                        'status' => Tasks::STATUS_AT_WORK],
                        'visible' => Yii::$app->user->identity->is_executor == 1],
                    ['label' => 'Создать задание', 'url' => ['tasks/create'],
                        'visible' => !Yii::$app->user->identity->is_executor == 1],
                    ['label' => 'Настройки', 'url' => ['user/edit']],
                ],
                'options' => [
                    'class' => 'nav-list'
                ],
                'itemOptions' => [
                    'class' => 'list-item'
                ],
                'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                'activateItems' => true,
                'activateParents' => true,
                'activeCssClass' => 'list-item--active',
            ]);
            ?>
        </div>
        <?php endif; ?>
    </nav>
    <?php if (!Yii::$app->user->isGuest) : ?>
    <div class="user-block">
        <a href="<?= Url::toRoute(['user/view',
            'id' => Yii::$app->user->identity->id]); ?>">
            <img class="user-photo" src="<?= Html::encode(empty($user->avatar) ?
                $defaultAvatar : $user->avatar); ?>" width="55"
                 height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name">
                <?= Html::encode(Yii::$app->user->identity->name); ?>
            </p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="<?= Url::to('/user/edit'); ?>"
                           class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="mailto:mail@taskforce.com" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::to('/landing/logout') ;?>"
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
