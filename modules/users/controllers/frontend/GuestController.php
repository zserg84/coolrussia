<?php

namespace modules\users\controllers\frontend;

use modules\geo\models\GeoCity;
use modules\geo\models\GeoCountry;
use modules\image\models\Image;
use modules\users\helpers\OAuthHelper;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use modules\users\models\frontend\ActivationForm;
use modules\users\models\frontend\RecoveryConfirmationForm;
use modules\users\models\frontend\RecoveryForm;
use modules\users\models\frontend\ResendForm;
use modules\users\models\frontend\User;
use modules\users\models\LoginForm;
use modules\users\models\Profile;
use modules\lang\models\Lang;
use modules\users\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Frontend controller for guest users.
 */
class GuestController extends Controller
{

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
     * Sign Up page.
     * If record will be successful created, user will be redirected to home page.
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = new User(['scenario' => 'signup']);
        $tmp_avatar = null;
        $geoCity = null;

        $attributes = OAuthHelper::GetAttributes();

        if ($user->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
            if ($user->validate()) {
                if ($user->save(false)) {
                    $profile = new Profile();
                    $profile->user_id = $user->id;
                    $profile->name = $user->name;
                    $profile->lang_id = Lang::getCurrent()->id;
                    $profile->save();
                    if ($user->use_avatar and isset($attributes['user_avatar'])) {
                        if ($image = Image::GetByUrl($attributes['user_avatar'])) {
                            $user->image_id = $image->id;
                            $user->save();
                        }
                    }

                    Yii::$app->user->login($user);

                    OAuthHelper::DropSession();

                    if ($this->module->requireEmailConfirmation === true) {
                        Yii::$app->session->setFlash(
                            'success',
                            Module::t(
                                'users',
                                'FRONTEND_FLASH_SUCCESS_SIGNUP_WITHOUT_LOGIN',
                                [
                                    'url' => Url::toRoute('resend')
                                ]
                            )
                        );
                    } else {
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash(
                            'success',
                            Module::t('users', 'FRONTEND_FLASH_SUCCESS_SIGNUP_WITH_LOGIN')
                        );
                    }
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_SIGNUP'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
        } elseif (isset($_GET['oauth'])) {
            if (isset($attributes['email'])) {
                $user->email = $attributes['email'];
            }
            if (isset($attributes['name'])) {
                $user->name = $attributes['name'];
            }
            if (isset($attributes['birthday'])) {
                $user->birthday = $attributes['birthday'];
            }
            if (isset($attributes['city_name'])) {
                /** @var $geoCity GeoCity */
                if ($geoCity = GeoCity::findByName($attributes['city_name'])) {
                    $user->city_id = $geoCity->id;
                    $user->country_id = $geoCity->country_id;
                    $user->city_name = $geoCity->getName();
                }
            }
            if (isset($attributes['user_avatar'])) {
                $tmp_avatar = $attributes['user_avatar'];
                $user->use_avatar = 1;
            }
        }

        if (!$user->city_id and isset($_SERVER['REMOTE_ADDR']) and ($ip = $_SERVER['REMOTE_ADDR'])) {
            if ($geoCity = GeoCity::getByIP($ip)) {
                $user->city_id = $geoCity->id;
                $user->city_name = $geoCity->getName(true);
                $user->country_id = $geoCity->country_id;
            } elseif ($country = GeoCountry::GetByIp($ip)) {
                $user->country_id = $country->id;
            }
        }

        $geoCountryList = GeoCountry::find()->orderBy('sort')->all();
        $geoCountryArr = ArrayHelper::map($geoCountryList, 'id', 'name_ru');

        return $this->render(
            'signup',
            [
                'user' => $user,
                'geoCountryArr' => $geoCountryArr,
                'tmp_avatar' => $tmp_avatar,
            ]
        );
    }


    /**
     * Resend email confirmation token page.
     */
    public function actionResend()
    {
        $model = new ResendForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->resend()) {
                    Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCESS_RESEND'));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_RESEND'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'resend',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Sign In page.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    return $this->goHome();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Activate a new user page.
     *
     * @param string $token Activation token.
     *
     * @return mixed View
     */
    public function actionActivation($token)
    {
        $model = new ActivationForm(['token' => $token]);

        if ($model->validate() && $model->activation()) {
            Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCESS_ACTIVATION'));
        } else {
            Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_ACTIVATION'));
        }

        return $this->goHome();
    }

    /**
     * Request password recovery page.
     */
    public function actionRecovery()
    {
        $model = new RecoveryForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCESS_RECOVERY'));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_RECOVERY'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'recovery',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Confirm password recovery request page.
     *
     * @param string $token Confirmation token
     *
     * @return mixed View
     */
    public function actionRecoveryConfirmation($token)
    {
        $model = new RecoveryConfirmationForm(['token' => $token]);

        if (!$model->isValidToken()) {
            Yii::$app->session->setFlash(
                'danger',
                Module::t('users', 'FRONTEND_FLASH_FAIL_RECOVERY_CONFIRMATION_WITH_INVALID_KEY')
            );
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash(
                        'success',
                        Module::t('users', 'FRONTEND_FLASH_SUCCESS_RECOVERY_CONFIRMATION')
                    );
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash(
                        'danger',
                        Module::t('users', 'FRONTEND_FLASH_FAIL_RECOVERY_CONFIRMATION')
                    );
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'recovery-confirmation',
            [
                'model' => $model
            ]
        );

    }

}