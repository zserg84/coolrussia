<?php

namespace modules\users\controllers\backend;

use vova07\admin\components\Controller;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use modules\users\models\backend\User;
use modules\users\models\backend\UserSearch;
use modules\users\models\Profile;
use modules\users\Module;
use Yii;
use yii\base\Request;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Default backend controller.
 */
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
                'roles' => ['BViewUsers']
            ]
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['create'],
            'roles' => ['BCreateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['update'],
            'roles' => ['BUpdateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['delete', 'batch-delete'],
            'roles' => ['BDeleteUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['fileapi-upload'],
            'roles' => ['BCreateUsers', 'BUpdateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['login-as'],
            'roles' => ['BUpdateUsers']
        ];
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['send-activate-mail'],
            'roles' => ['BUpdateUsers']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
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
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->avatarsTempPath
            ]
        ];
    }

    /**
     * Users list page.
     */
    function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Create user page.
     */
    /*public function actionCreate()
    {
        $user = new User(['scenario' => 'admin-create']);
        $profile = new Profile();
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {
                $user->populateRelation('profile', $profile);
                if ($user->save(false)) {
                    return $this->redirect(['update', 'id' => $user->id]);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return array_merge(ActiveForm::validate($user), ActiveForm::validate($profile));
            }
        }

        return $this->render('create', [
                'user' => $user,
                'profile' => $profile,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }*/

    /**
     * Update user page.
     *
     * @param integer $id User ID
     *
     * @return mixed View
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $user->setScenario('admin-update');
        $statusArray = User::getStatusArray();
        $roleArray = User::getRoleArray();

        if ($user->load(Yii::$app->request->post())) {
            if ($user->validate()) {
                if (!$user->save(false)) {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                }
                return $this->refresh();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
        }

        return $this->render('update', [
                'user' => $user,
                'roleArray' => $roleArray,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Delete user page.
     *
     * @param integer $id User ID
     *
     * @return mixed View
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Delete multiple users page.
     */
    public function actionBatchDelete()
    {
        if (($ids = Yii::$app->request->post('ids')) !== null) {
            $models = $this->findModel($ids);
            foreach ($models as $model) {
                $model->delete();
            }
            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    /**
     * Find model by ID
     *
     * @param integer|array $id User ID
     *
     * @return \modules\users\models\backend\User User
     * @throws HttpException 404 error if user was not found
     */
    protected function findModel($id)
    {
        if (is_array($id)) {
            /** @var User $user */
            $model = User::findIdentities($id);
        } else {
            /** @var User $user */
            $model = User::findIdentity($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }


    public function actionLoginAs($id) {
        if ($id !== Yii::$app->getUser()->getId()) {
            //if (Yii::$app->user->can('BLoginAs')) {
            if ($user = \modules\users\models\User::find()->where(['id'=>$id])->one()) {
                Yii::$app->user->login($user, 3600 * 24 * 30);
                return $this->redirect('/');
            }
            //}
        }
        $this->redirect(Url::toRoute(['update', 'id'=>$id]));
    }


    public function actionSendActivateMail() {
        $request = Yii::$app->request;
        if ($request->isPost and ($id = intval($request->post('id')))) {
            /** @var \modules\users\models\frontend\User $user */
            if ($user = \modules\users\models\frontend\User::find()->where(['id'=>$id])->one()) {
                if ($user->status_id == User::STATUS_INACTIVE) {
                    if ($user->sendMail()) {
                        echo 'ok';
                    }
                }
            }
        }
    }
}
