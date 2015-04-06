<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.02.15
 * Time: 15:20
 */

namespace common\actions;

/**
 * action для удаления нескольких записей
 */
class BatchDeleteAction extends Action
{
    public $batchParamName = 'ids';

    public function run(){
        if (($ids = \Yii::$app->request->post($this->batchParamName)) !== null) {
            $modelClass = $this->modelClass;
            $models = $this->findModel($modelClass, $ids);
            foreach ($models as $model) {
                $model->delete();
            }
            return $this->controller->redirect([$this->redirectUrl]);
        } else {
            throw new HttpException(400);
        }
    }

    public static function findModel($modelClass, $id, $idName = null)
    {
        if (is_array($id)) {
            /** @var \modules\lang\models\backend\Lang $model */
            $model = $modelClass::findAll($id);
        } else {
            /** @var \modules\lang\models\backend\Lang $model */
            $model = $modelClass::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    public function loadData($model, $post){

    }
} 