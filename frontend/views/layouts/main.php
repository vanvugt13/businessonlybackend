<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\models\User;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [];
    if(!Yii::$app->user->isGuest){
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Bedrijven', 'url' => ['/company/index']],
            ['label' => 'Gebruikers', 'url' => ['/user/index']],
        //  ['label' => 'Nieuws', 'url' => ['/news/index']],
            ['label' => 'Items', 'url' => ['/post/index']],
            ['label' => 'Geclaimde wedstrijden', 'url' => ['/event-sponsor/index']],
            ['label' => 'Lijst met aanmeldingen', 'url' => ['/subscribe/index']],
            ['label' => 'Instellingen', 'url' => ['/setting/index'],'visible'=>Yii::$app->user->identity->type == User::TYPE_BUSINESSONLY_ADMIN],
            ['label' => 'Emailopmaak', 'url' => ['/setting-email/index'],'visible'=>Yii::$app->user->identity->type == User::TYPE_BUSINESSONLY_ADMIN],
            ['label' => 'Sponsortypen', 'url' => ['/setting-sponsortype/index'],'visible'=>Yii::$app->user->identity->type == User::TYPE_BUSINESSONLY_ADMIN]
        ];
    }

    
    if (Yii::$app->user->isGuest) {
       // $menuItems[] = ['label' => 'Aanmelden', 'url' => ['/site/signup']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Inloggen',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Uitloggen (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?php // Breadcrumbs::widget([
            //'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        //]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
