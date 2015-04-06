<?php

/**
 * Theme home layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use modules\themes\site\widgets\Alert;
use modules\themes\Module;

?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head') ?>
    </head>
    <body>
    <?=$this->render('//layouts/_su_panel')?>
    <?php $this->beginBody(); ?>

    <header class="navbar navbar-inverse navbar-fixed-top wet-asphalt" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only"><?= Module::t('themes-site', 'Toggle navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
                    <?= Yii::$app->name ?>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <?= $this->render('//layouts/top-menu') ?>
            </div>
        </div>
    </header>
    <!--/header-->

    <?= Alert::widget(); ?>

    <?= $content ?>

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2014 <?= Yii::$app->name ?>. <?= Module::t('themes-site', 'All Rights Reserved') ?>.
                </div>
                <div class="col-sm-6">
                    <?= $this->render('//layouts/_footer_menu') ?>
                </div>
            </div>
        </div>
    </footer>
    <!--/#footer-->

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>