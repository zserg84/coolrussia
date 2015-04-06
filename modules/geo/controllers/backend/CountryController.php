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
use modules\geo\models\backend\CountrySearch;
use modules\geo\models\GeoCountry;
use vova07\admin\components\Controller;
use yii\helpers\Json;
use yii\helpers\Url;

class CountryController extends Controller
{

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => CountrySearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GeoCountry::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GeoCountry::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GeoCountry::className(),
            ],
            'batch-delete' => [
                'class' => BatchDeleteAction::className(),
                'modelClass' => GeoCountry::className(),
            ],
        ];
    }

    /*
     * для kartik-depdrop
     * */
    public function actionGetRegions(){
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $countryId = $parents[0];
                $country = GeoCountry::findOne($countryId);
                $regions = $country->geoRegions;
                $out = [];
                foreach($regions as $k=>$region){
                    $out[$k]['id']=$region->id;
                    $out[$k]['name']=$region->name_ru;
                }

                $data = [
                    'out'=>$out,
                    'selected'=>''
                ];
                echo Json::encode(['output'=>$data['out'], 'selected'=>$data['selected']]);
                \Yii::$app->end();
            }
        }
    }
} 