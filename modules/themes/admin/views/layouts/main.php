<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use modules\themes\admin\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use modules\themes\Module;

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head') ?>
    </head>
    <body class="skin-blue">
    <?php $this->beginBody(); ?>
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?= Yii::$app->homeUrl ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?= Yii::$app->name ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?= Module::t('themes-admin', 'Toggle navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div style="float:left;font-size:19px;color:#fff;margin:11px 0 0 12px;">
                    <?= $this->title.((isset($this->params['subtitle'])) ? Html::tag('small', $this->params['subtitle'], ['style'=>'margin-left:7px;color:#ccc;']) : '')?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="/" target="_blank" style="font-size:17px;color:#fff;"><?=Module::t('themes-admin', 'GO_TO_SITE')?></a>
                </div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?= Yii::$app->user->identity->name?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?= Html::a(
                                            Module::t('themes-admin', 'Profile'),
                                            ['/users/default/update', 'id' => Yii::$app->user->id],
                                            ['class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?= Html::a(
                                            Module::t('themes-admin', 'Sign out'),
                                            ['/users/user/logout'],
                                            ['class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <?
                    /*/
                    <div class="user-panel">
                        <?php if (Yii::$app->user->identity->profile->avatar_url) : ?>
                            <div class="pull-left image">
                                <?= Html::img(Yii::$app->user->identity->profile->urlAttribute('avatar_url'), ['class' => 'img-circle', 'alt' => Yii::$app->user->identity->username]) ?>
                            </div>
                        <?php endif; ?>
                        <div class="pull-left info">
                            <p>
                                <?= Module::t('themes-admin', 'Hello, {name}', ['name' => Yii::$app->user->identity->name]) ?>
                            </p>
                            <a>
                                <i class="fa fa-circle text-success"></i> <?= Module::t('themes-admin', 'Online') ?>
                            </a>
                        </div>
                    </div>
                    /**/
                    ?>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?= $this->render('//layouts/sidebar-menu') ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <?=$this->renderPhpFile(Yii::getAlias('@modules').'/themes/site/views/layouts/_su_panel.php')?>
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <?= Breadcrumbs::widget(
                        [
                            'homeLink' => [
                                'label' => '<i class="fa fa-dashboard"></i> ' . Module::t('themes-admin', 'Home'),
                                'url' => '/backend/'
                            ],
                            'encodeLabels' => false,
                            'tag' => 'ol',
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                        ]
                    ) ?>
                    <br style="clear:both;" />
                </section>

                <!-- Main content -->
                <section class="content">
                    <?= Alert::widget(); ?>
                    <?= $content ?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>