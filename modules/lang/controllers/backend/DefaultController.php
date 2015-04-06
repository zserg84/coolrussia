<?php

namespace modules\lang\controllers\backend;

use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\lang\models\backend\LangSearch;
use modules\lang\models\Lang;
use yii\filters\VerbFilter;
use vova07\admin\components\Controller;
use yii\helpers\Url;

class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['BViewLang']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateLang']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateLang']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteLang']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['get', 'post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => LangSearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Lang::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Lang::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Lang::className(),
            ],
        ];
    }

    public function actionBatchDelete()
    {
        if (($ids = \Yii::$app->request->post('ids')) !== null) {
            $models = $this->findModel($ids);
            foreach ($models as $model) {
                $model->delete();
            }
            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    protected function findModel($id)
    {
        if (is_array($id)) {
            /** @var \modules\lang\models\backend\Lang $model */
            $model = Lang::findAll($id);
        } else {
            /** @var \modules\lang\models\backend\Lang $model */
            $model = Lang::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
}
