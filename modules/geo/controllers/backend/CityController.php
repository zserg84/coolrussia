<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.02.15
 * Time: 15:52
 */

namespace modules\geo\controllers\backend;

use common\actions\BatchDeleteAction;
use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\geo\models\backend\CitySearch;
use modules\geo\models\GeoCity;
use modules\geo\models\GeoCountry;
use modules\geo\models\GeoRegion;
use vova07\admin\components\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class CityController extends Controller
{

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => CitySearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GeoCity::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GeoCity::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GeoCity::className(),
            ],
            'batch-delete' => [
                'class' => BatchDeleteAction::className(),
                'modelClass' => GeoCity::className(),
            ],
        ];
    }
} 