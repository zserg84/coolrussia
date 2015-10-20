<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use modules\themes\site\widgets\Alert;
use yii\widgets\Breadcrumbs;
use modules\themes\Module as ThemeModule;
use modules\themes\tender\ThemeAsset;
use modules\contract\Module as ContractModule;
use frontend\widgets\Lang\LangWidget;
use modules\users\widgets\quicklogin\QuickLoginWidget;
use modules\users\widgets\userinfo\UserinfoWidget;
use \modules\contract\widgets\filter\FilterSpecWidget;
use \modules\contract\widgets\filter\FilterDirectionWidget;
use \modules\contract\widgets\filter\FilterTerritoryWidget;

$imgPath = ThemeAsset::imgSrc('', 'images');

?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head') ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>

    <header>
        <div class="container-fluid header-top">
            <div class="row">
                <!-- logo -->
                <div id="logo" class="col-xs-2 col-md-2 col-lg-2">
                    <a href="index.html"></a>
                </div>
                <!-- bunner -->
                <div class="col-sm-8">
                    <div class="bunner-block">
                        <a href="#">
                            <img src="<?=$imgPath.'bunner1.png'?>" alt="" />
                        </a>
                    </div>
                </div>

                <!-- login-block -->
                <div class="login-block col-sm-2">
                    <div class="row login-wrapper">
                        <?
                        if(Yii::$app->getUser()->getIsGuest()){
                            echo QuickLoginWidget::widget();
                        }
                        else{
                            echo UserinfoWidget::widget();
                        }
                        ?>
                    </div>
                </div>

                <!-- end login-block -->
            </div>
        </div>
        <!-- end header-top -->

        <!-- nav -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <?= LangWidget::widget();?>

                <div class="navbar navbar-right col-sm-10">
                    <form class="navbar-form navbar-right search-block" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= ThemeModule::t('Horizontal menu ALL pages', 'SEARCH') ?>">
                        </div>
                    </form>
                    <ul class="nav navbar-nav">
                        <li><a href="about.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'ABOUT_MENU_ALL_PAGES') ?></a></li>
                        <li><a href="news.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'NEWS_MENU_ALL_PAGES') ?></a></li>
                        <li><a href="about.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'TECHNOLOGIES_MENU_ALL_PAGES') ?></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Напрвление 1</a></li>
                            </ul>
                        </li>
                        <li><a href="news.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'ARTICLES_MENU_ALL_PAGES') ?></a></li>
                        <li><a href="news.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'TRAININGS_MENU_ALL_PAGES') ?></a></li>
                        <li><a href="about.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'PARTNERS_MENU_ALL_PAGES') ?></a></li>
                        <li><a href="about.html"><?= ThemeModule::t('Horizontal menu ALL pages', 'CONTACTS_MENU_ALL_PAGES') ?></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!--/header-->

<!--    --><?php //if (!isset($this->params['noTitle'])) : ?>
<!--        <section id="title" class="emerald">-->
<!--            <div class="container">-->
<!--                <div class="row">-->
<!--                    <div class="col-sm-6">-->
<!--                        <h1>--><?//= $this->title ?><!--</h1>-->
<!--                        --><?php //if (isset($this->params['subtitle'])) : ?>
<!--                            <p>--><?//= $this->params['subtitle'] ?><!--</p>-->
<!--                        --><?php //endif; ?>
<!--                    </div>-->
<!--                    <div class="col-sm-6">-->
<!--                        --><?//=
//                        Breadcrumbs::widget(
//                            [
//                                'options' => [
//                                    'class' => 'breadcrumb pull-right'
//                                ],
//                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
//                            ]
//                        ) ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </section><!--/#title-->
<!--    --><?php //endif; ?>

<!--    --><?//= Alert::widget(); ?>

    <div class="container-fluid content-wrapper">
        <div class="row">
            <div class="col-sm-3 col-lg-2">
                <div class="leftbar">

                    <!-- filtr -->
                    <div class="filtr">
                        <?=\modules\contract\widgets\filter\FilterWidget::widget([
                            'filterParams' => [
                                [
                                    'name' => ContractModule::t('ALL_INTERFACES', 'DIRECTIONS_FILTER'),
                                    'filterView' => '_direction',
                                ],
                                [
                                    'name' => ContractModule::t('ALL_INTERFACES', 'COMPANY_SPECIALIZATION_FILTER'),
                                    'filterView' => '_specialization',
                                ],
                                [
                                    'name' => ContractModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER'),
                                    'filterView' => '_territory',
                                ],
                            ]
                        ])?>
                    </div>
                    <!-- end filtr -->

                    <!-- bunner-block -->
                    <div class="bunner-block">
                        <a href="#">
                            <img src="<?=$imgPath?>leftbar-bunner.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="centerbar col-sm-6 col-lg-8">
                <div class="top-nav off-sub">
                    <?=ContractModule::getUserMenu()?>
                    <p>
                    <?
                    if(!Yii::$app->getUser()->isGuest)
                        echo \modules\contract\widgets\order\OrderCreateWidget::widget();
                    ?>
                    </p>
                </div>
                <div class="centerbar-wrapper">
                    <?= $content ?>
                </div>
            </div>

            <div class="col-sm-3 col-lg-2">
                <div class="rightbar">
                    <div class="block bunner">
                        <a href="#">
                            <img src="<?=$imgPath?>rightbar-bunner.png" alt="">
                        </a>
                    </div>

                    <div class="block forums">
                        <p class="title"><?=ThemeModule::t('ALL_INTERFACES', 'FORUMS')?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>