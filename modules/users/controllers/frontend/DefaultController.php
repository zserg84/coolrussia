<?php

namespace modules\users\controllers\frontend;

use Geocoder\Exception\HttpException;
use modules\users\models\frontend\Email;
use modules\users\models\User;
use modules\users\Module;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Default frontend controller.
 */
class DefaultController extends Controller
{

    /**
     * Confirm new e-mail address.
     *
     * @param string $key Confirmation token
     *
     * @return mixed View
     */
    public function actionEmail($key)
    {
        $model = new Email(['token' => $key]);

        if ($model->isValidToken() === false) {
            Yii::$app->session->setFlash(
                'danger',
                Module::t('users', 'FRONTEND_FLASH_FAIL_NEW_EMAIL_CONFIRMATION_WITH_INVALID_KEY')
            );
        } else {
            if ($model->confirm()) {
                Yii::$app->session->setFlash(
                    'success',
                    Module::t('users', 'FRONTEND_FLASH_SUCCESS_NEW_EMAIL_CONFIRMATION')
                );
            } else {
                Yii::$app->session->setFlash(
                    'danger',
                    Module::t('users', 'FRONTEND_FLASH_FAIL_NEW_EMAIL_CONFIRMATION')
                );
            }
        }

        return $this->goHome();
    }


    public function actionView($id = null) {
        if (is_null($id)) {
            if (Yii::$app->getUser()->isGuest) {
                return $this->redirect(Url::toRoute('/users/guest/login'));
            } else {
                $id = Yii::$app->getUser()->getId();
            }
        }

        /** @var \modules\users\models\User $user */
        if ($user = User::find()->where(['id'=>$id])->one()) {
            return $this->render('view', compact('user'));
        }
        throw new HttpException('User not found', 404);
    }

}
