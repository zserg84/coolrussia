<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.02.15
 * Time: 14:57
 */

namespace modules\geo\controllers\backend;

use common\actions\BatchDeleteAction;
use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\geo\models\backend\RegionSearch;
use modules\geo\models\GeoRegion;
use vova07\admin\components\Controller;
use yii\helpers\Url;

class RegionController extends Controller
{

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => RegionSearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GeoRegion::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GeoRegion::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GeoRegion::className(),
            ],
            'batch-delete' => [
                'class' => BatchDeleteAction::className(),
                'modelClass' => GeoRegion::className(),
            ],
        ];
    }
} 