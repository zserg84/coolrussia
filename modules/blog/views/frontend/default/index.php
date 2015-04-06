<?php

/**
 * Blog list page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider DataProvider
 */

use modules\blog\Module;
use yii\widgets\ListView;

$this->title = Module::t('blogs', 'FRONTEND_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title; ?>
<div class="row">

    <aside class="col-sm-4 col-sm-push-8">

    </aside>

    <div class="col-sm-8 col-sm-pull-4">
        <?= ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'itemView' => '_index_item',
                'options' => [
                    'class' => 'blog'
                ],
                'itemOptions' => [
                    'class' => 'blog-item',
                    'tag' => 'article'
                ]
            ]
        ); ?>
    </div>
</div>