<?php

/**
 * Theme home layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use frontend\widgets\alert\Alert;
use modules\themes\Module as ThemeModule;
use modules\themes\tender\ThemeAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$imgPath = ThemeAsset::imgSrc('', 'images');
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
                            echo \modules\users\widgets\quicklogin\QuickLoginWidget::widget();
                            }
                        else{
                            echo \modules\users\widgets\userinfo\UserinfoWidget::widget();
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
                <?= \frontend\widgets\Lang\LangWidget::widget();?>

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
        <!-- end nav -->
    </header>
    <!--/header-->

    <?
    $slides = [
        [
            'header' => ThemeModule::t('Homepage', 'TEXT_FOR_HEADING_OF_SLIDER1'),
            'text' => ThemeModule::t('Homepage', 'TEXT_FOR_SLIDER1'),
        ],
        [
            'header' => ThemeModule::t('Homepage', 'TEXT_FOR_HEADING_OF_SLIDER2'),
            'text' => ThemeModule::t('Homepage', 'TEXT_FOR_SLIDER2'),
        ],
        [
            'header' => ThemeModule::t('Homepage', 'TEXT_FOR_HEADING_OF_SLIDER3'),
            'text' => ThemeModule::t('Homepage', 'TEXT_FOR_SLIDER3'),
        ],
        [
            'header' => ThemeModule::t('Homepage', 'TEXT_FOR_HEADING_OF_SLIDER4'),
            'text' => ThemeModule::t('Homepage', 'TEXT_FOR_SLIDER4'),
        ],
        [
            'header' => ThemeModule::t('Homepage', 'TEXT_FOR_HEADING_OF_SLIDER5'),
            'text' => ThemeModule::t('Homepage', 'TEXT_FOR_SLIDER5'),
        ],
    ]
    ?>
    <div class="carousel slide main-slider" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?foreach($slides as $slide):?>
            <div class="item active">
                <img src="<?=$imgPath?>slider-img.jpg" alt="" height="220">
                <div class="carousel-caption col-sm-3 pull-right">
                    <p class="title"><?=$slide['header']?></p>
                    <p><?=$slide['text']?></p>
                </div>
            </div>
            <?endforeach?>
        </div>
    </div>

    <?= Alert::widget(); ?>

    <div class="container-fluid content-wrapper">
        <?= $content ?>

        <div class="row">
            <div class="categories">
                <div class="col-sm-4">
                    <p class="title"><?=ThemeModule::t('Homepage', 'ABOUT_THE_SYSTEM')?></p>
                    <a href="<?=Url::toRoute(['/site/default/about/'])?>"><img src="<?=$imgPath?>categories-about-ico.png" alt="" width="106" height="106"></a>
                    <p><?=ThemeModule::t('Homepage', 'TEXT_ABOUT_THE_SYSTEM')?></p>
                </div>
                <div class="col-sm-4">
                    <p class="title"><?=ThemeModule::t('Homepage', 'ALL_PERFORMERS')?></p>
                    <a href="<?=Url::toRoute(['/contract/performer/list/'])?>"><img src="<?=$imgPath?>categories-performer-ico.png" alt="" width="106" height="106"></a>
                    <p><?=ThemeModule::t('Homepage', 'TEXT_ALL_PERFORMERS')?></p>
                </div>
                <div class="col-sm-4">
                    <p class="title"><?=ThemeModule::t('Homepage', 'ALL_ORDERS')?></p>
                    <a href="<?=Url::toRoute(['/contract/order/list/'])?>"><img src="<?=$imgPath?>categories-order-ico.png" alt="" width="106" height="106"></a>
                    <p><?=ThemeModule::t('Homepage', 'TEXT_ALL_ORDERS')?></p>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>