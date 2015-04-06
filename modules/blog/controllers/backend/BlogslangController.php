<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 13.02.15
 * Time: 16:58
 */

namespace modules\blog\controllers\backend;

use common\actions\DeleteAction;
use Yii;
use common\actions\IndexAction;
use modules\blog\models\backend\BlogsLangSearch;
use modules\blog\models\backend\BlogsLang;
use modules\lang\models\Lang;
use vova07\admin\components\Controller;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use vova07\imperavi\actions\GetAction as ImperaviGet;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;

class BlogslangController extends Controller
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
                'roles' => ['BViewBlogsLang']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateBlogsLang']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateBlogsLang']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteBlogsLang']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['imperavi-get', 'imperavi-image-upload', 'imperavi-file-upload', 'fileapi-upload'],
            'roles' => ['BCreateBlogsLang', 'BUpdateBlogsLang']
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
        $blogId = \Yii::$app->getRequest()->get('id');
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
                'modelClass' => BlogsLang::className(),
            ],
        ];
    }

    /**
     * Delete multiple posts page.
     *
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function actionBatchDelete()
    {
        if (($ids = \Yii::$app->request->post('ids')) !== null) {
            $modelClass = BlogsLang::className();
            $models = $this->findModel($modelClass, $ids);
            foreach ($models as $model) {
                $model->delete();
            }
//            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    public function actionIndex()
    {

        $blogId = Yii::$app->getRequest()->get('id');
        $searchModel = new BlogsLangSearch();
        $searchModel->blog_id = $blogId;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        echo $this->renderPartial('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'blogId' => $blogId,
        ]);
    }

    /**
     * Create blogslang page.
     */
    public function actionCreate($blogId)
    {
        $model = new BlogsLang(['scenario' => 'admin-create']);

        if ($model->load(\Yii::$app->request->post())) {
            $model->blog_id = $blogId;
            if ($model->validate()) {
                if ($model->save(false)) {
//                    $return = ['update', 'id' => $model->id];
                    $return = ['/blogs/default/update', 'id'=>$blogId];
                    return $this->redirect($return);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('blogslang', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForBlog($blogId);

        return $this->render('create', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    /**
     * Update blogslang page.
     *
     * @param integer $id Post ID
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelClass = BlogsLang::className();
        $model = $this->findModel($modelClass, $id);
        $model->setScenario('admin-update');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
//                    return $this->refresh();
                    $return = ['/blogs/default/update', 'id'=>$model->blog_id];
                    return $this->redirect($return);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('blogslang', 'BACKEND_FLASH_FAIL_ADMIN_UPDATE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        $langArr = Lang::langForBlog($model->blog_id, $model->lang_id);

        return $this->render('update', [
            'model' => $model,
            'langArr' => $langArr,
        ]);
    }

    /**
     * Find model by ID.
     * @param object $modelClass
     * @param integer|array $id Post ID
     * @param string $idName
     *
     * @return \modules\blog\models\backend\Blog Model
     *
     * @throws HttpException 404 error if post not found
     */
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