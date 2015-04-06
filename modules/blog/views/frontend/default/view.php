<?php

/**
 * Blog page view.
 *
 * @var \yii\web\View $this View
 * @var \modules\blog\models\frontend\Blog $model Model
 */

use modules\comments\widgets\Comments;
use modules\blog\Module;
use yii\helpers\Html;

$this->title = $model->getTitle();
$this->params['breadcrumbs'] = [
    [
        'label' => Module::t('blogs', 'BACKEND_INDEX_TITLE'),
        'url' => ['index']
    ],
    $this->title
]; ?>
<div class="row">
    <aside class="col-sm-4 col-sm-push-8"></aside>

    <div class="col-sm-8 col-sm-pull-4">
        <div class="blog">
            <div class="blog-item">
                <?php if ($model->preview_url) : ?>
                    <?= Html::img(
                        $model->urlAttribute('preview_url'),
                        ['class' => 'img-responsive img-blog', 'width' => '100%', 'alt' => $model->getTitle()]
                    ) ?>
                <?php endif; ?>
                <div class="blog-content">
                    <h3><?= $model->getTitle() ?></h3>
                    <div class="entry-meta">
                        <span><i class="icon-calendar"></i> <?= $model->created ?></span>
                        <span><i class="icon-eye-open"></i> <?= $model->views ?></span>
                    </div>
                    <?= $model->getContent() ?>

                    <?php if (Yii::$app->base->hasExtension('comments') && Yii::$app->user->can('viewComments')) :
                        echo Comments::widget(
                            [
                                'model' => $model,
                                'jsOptions' => [
                                    'offset' => 80
                                ]
                            ]
                        );
                    endif; ?>

                </div>
            </div><!--/.blog-item-->
        </div>
    </div><!--/.col-md-8-->
</div><!--/.row-->