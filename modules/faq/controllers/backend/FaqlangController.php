<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 13.02.15
 * Time: 11:26
 */

namespace modules\faq\controllers\backend;

use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use modules\faq\models\backend\FaqLangSearch;
use modules\faq\models\FaqLang;
use modules\faq\Module;
use modules\lang\models\Lang;
use vova07\admin\components\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use vova07\imperavi\actions\GetAction as ImperaviGet;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;

class FaqlangController extends Controller
{

    public function actions()
    {
        return [
            'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileUrl,
                'path' => $this->module->filePath,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->imagesTempPath
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => FaqLang::className(),
            ],
        ];
    }

    public function actionIndex()
    {

        $faqId = \Yii::$app->getRequest()->get('id');
        $searchModel = new FaqLangSearch();
        $searchModel->faq_id = $faqId;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        echo $this->renderPartial('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'faqId' => $faqId,
        ]);
    }

    public function actionCreate($faqId)
    {
        $model = new FaqLang();

        if ($model->load(\Yii::$app->request->post())) {
            $model->faq_id = $faqId;
            if ($model->validate()) {
                if ($model->save(false)) {
                    $return = ['/faq/default/update', 'id'=>$faqId];
                    return $this->redirect($return);
                } else {
                    \Yii::$app->session->setFlash('danger', Module::t('faqlang', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                    return $this->refresh();
                }
            } elseif (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForFaq($faqId);

        return $this->render('create', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    public function actionUpdate($id)
    {
        $modelClass = FaqLang::className();
        $model = $this->findModel($modelClass, $id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    $return = ['/faq/default/update', 'id'=>$model->faq_id];
                    return $this->redirect($return);
                } else {
                    \Yii::$app->session->setFlash('danger', Module::t('faqlang', 'BACKEND_FLASH_FAIL_ADMIN_UPDATE'));
                    return $this->refresh();
                }
            } elseif (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForFaq($model->faq_id, $model->lang_id);

        return $this->render('update', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    public function actionBatchDelete()
    {
        if (($ids = \Yii::$app->request->post('ids')) !== null) {
            $modelClass = FaqLang::className();
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
            /** @var \modules\blog\models\backend\Blog $model */
            $model = $modelClass::findAll($id);
        } else {
            if(!$id)
                $id = \Yii::$app->getRequest()->getQueryParam($idName);
            /** @var \modules\blog\models\backend\Blog $model */
            $model = $modelClass::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
} 