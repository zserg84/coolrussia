<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 08.05.15
 * Time: 14:33
 */

namespace modules\direction\controllers\backend;


use common\actions\DeleteAction;
use modules\direction\models\Directionlang;
use modules\direction\models\search\DirectionlangSearch;
use modules\direction\Module;
use modules\lang\models\Lang;
use vova07\admin\components\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\HttpException;
use yii\web\Response;

class DirectionlangController extends Controller
{

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Directionlang::className(),
            ],
        ];
    }

    public function actionIndex()
    {

        $directionId = \Yii::$app->getRequest()->get('id');
        $searchModel = new DirectionlangSearch();
        $searchModel->direction_id = $directionId;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        echo $this->renderPartial('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'directionId' => $directionId,
        ]);
    }

    public function actionCreate($directionId)
    {
        $model = new Directionlang();

        if ($model->load(\Yii::$app->request->post())) {
            $model->direction_id = $directionId;
            if ($model->validate()) {
                if ($model->save(false)) {
                    $return = ['/direction/default/update', 'id'=>$directionId];
                    return $this->redirect($return);
                } else {
                    \Yii::$app->session->setFlash('danger', Module::t('directionlang', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                    return $this->refresh();
                }
            } elseif (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForDirection($directionId);

        return $this->render('create', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    public function actionUpdate($id)
    {
        $modelClass = Directionlang::className();
        $model = $this->findModel($modelClass, $id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    $return = ['/direction/default/update', 'id'=>$model->direction_id];
                    return $this->redirect($return);
                } else {
                    \Yii::$app->session->setFlash('danger', Module::t('directionlang', 'BACKEND_FLASH_FAIL_ADMIN_UPDATE'));
                    return $this->refresh();
                }
            } elseif (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForDirection($model->direction_id, $model->lang_id);

        return $this->render('update', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    public function actionBatchDelete()
    {
        if (($ids = \Yii::$app->request->post('ids')) !== null) {
            $modelClass = Directionlang::className();
            $models = $this->findModel($modelClass, $ids);
            foreach ($models as $model) {
                $model->delete();
            }
            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    public function findModel($modelClass, $id, $idName=null)
    {
        if (is_array($id)) {
            $model = $modelClass::findAll($id);
        } else {
            if(!$id)
                $id = \Yii::$app->getRequest()->getQueryParam($idName);
            $model = $modelClass::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
} 