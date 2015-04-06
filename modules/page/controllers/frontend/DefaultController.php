<?php

namespace modules\page\controllers\frontend;

use yii\web\Controller;
use modules\lang\models\Lang;
use modules\page\models\Page;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    public function actionView($url)
    {
        $lang_id = Lang::getCurrent()->id;
        /** @var Page $page */
        $page = Page::find()->where('url = :url AND lang_id = :lang_id', [':url'=>$url, ':lang_id'=>$lang_id])->one();
        if ($page) {
            echo $this->render('view', compact('page'));
        } else {
            throw new NotFoundHttpException();
        }
    }

} 