<?php

namespace modules\page\controllers\backend;

use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\page\models\PageSearch;
use modules\page\models\Page;
use vova07\admin\components\Controller;
use yii\helpers\Url;

class DefaultController extends Controller {

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => PageSearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Page::className(),
                'redirectUrl' => Url::toRoute('index'),
                'ajaxValidation' => true,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Page::className(),
                'redirectUrl' => Url::toRoute('index'),
                'ajaxValidation' => true,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Page::className(),
            ],
        ];
    }

}