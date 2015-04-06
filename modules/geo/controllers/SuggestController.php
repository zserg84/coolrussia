<?php

namespace modules\geo\controllers;

use Yii;
use \modules\geo\models\GeoCity;
use \yii\helpers\Json;

class SuggestController extends \yii\base\Controller {

    public function actionCity() {
        $request = Yii::$app->request;
        $term = trim($request->get('q')); // 'term'
        $output = [];
        if ($term) {
            $query = GeoCity::find()->where('confirm = 1 AND (name_ru LIKE :name OR name_en LIKE :name)', [':name' => $term . '%']);
            if ($country_id = $request->get('country_id')) {
                $query->andWhere('country_id = :country_id', [':country_id' => $country_id]);
            }
            $query->orderBy('sort ASC');
            $query->limit(10);
            $query->with(array('region', 'country'));
            $cityList = $query->all();
            /** @var $city GeoCity */
            foreach ($cityList as $city) {
                $output[] = ['id'=>$city->id, 'value'=>$city->getName(), 'label'=>$city->getName(true)]; // for AutoComplete
//                $output[] = ['id'=>$city->id, 'text'=>$city->getName(true)]; // for Select2
//                $output[] = ['id'=>$city->id, 'name'=>$city->getFullName()]; // for TokenInput
            }
        }
        echo Json::encode($output);
    }
}