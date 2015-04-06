<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 13.02.15
 * Time: 13:19
 */

namespace modules\faq\controllers\frontend;

use common\actions\IndexAction;
use modules\faq\models\FaqSearch;
use yii\web\Controller;

class DefaultController extends Controller
{

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => FaqSearch::className(),
                'searchParams' => ['FaqSearch'=>['status'=>1]],
            ],
        ];
    }

} 