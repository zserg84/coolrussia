<?php

namespace modules\users\controllers\frontend;

use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use modules\users\models\frontend\User;
use modules\users\models\frontend\Email;
use modules\users\models\frontend\PasswordForm;
use modules\users\models\Profile;
use modules\users\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Frontend controller for authenticated users.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
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
     * Log Out page.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Change password page.
     */
    public function actionPassword()
    {
        $model = new PasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->password()) {
                    Yii::$app->session->setFlash(
                        'success',
                        Module::t('users', 'FRONTEND_FLASH_SUCCESS_PASSWORD_CHANGE')
                    );
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_PASSWORD_CHANGE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'password',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Request email change page.
     */
    public function actionEmail()
    {
        $model = new Email();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCES_EMAIL_CHANGE'));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_EMAIL_CHANGE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'email',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Profile updating page.
     */
    public function actionUpdate()
    {
        if (!$user = User::GetCurrent()) {
            throw new NotFoundHttpException;
        }

        if ($user->load(Yii::$app->request->post())) {
            if ($user->validate()) {
                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCES_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_UPDATE'));
                }
                return $this->refresh();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
        }

        return $this->render(
            'update',
            [
                'user' => $user
            ]
        );
    }

}
