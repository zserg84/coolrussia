<?php

namespace modules\translations\controllers\backend;

use common\actions\CreateAction;
use modules\translations\models\Message;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use modules\translations\models\search\SourceMessageSearch;
use modules\translations\models\SourceMessage;
use modules\translations\Module;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new SourceMessage();
        if($model->load(Yii::$app->getRequest()->post())){
            if($model->save()){
                $model->initMessages();
                if (Model::loadMultiple($model->messages, Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {
                    $model->saveMessages();
                    Yii::$app->getSession()->setFlash('success', Module::t('translations', 'Created'));
                    return $this->redirect(['create', 'id' => $model->id]);
                }
            }
        }
        else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var SourceMessage $model */
        $model = $this->findModel($id);
        $model->initMessages();
        if($model->load(Yii::$app->getRequest()->post())){
            $model->save();
        }
        if (Model::loadMultiple($model->messages, Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {
            $model->saveMessages();
            Yii::$app->getSession()->setFlash('success', Module::t('translations', 'Updated'));
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * @param array|integer $id
     * @return SourceMessage|SourceMessage[]
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $query = SourceMessage::find()->where('id = :id', [':id' => $id]);
        $models = is_array($id)
            ? $query->all()
            : $query->one();
        if (!empty($models)) {
            return $models;
        } else {
            throw new NotFoundHttpException(Module::t('translations', 'The requested page does not exist'));
        }
    }
}
