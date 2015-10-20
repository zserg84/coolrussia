<?php

namespace modules\site\controllers;

use modules\site\components\Controller;
use modules\site\models\ContactForm;
use modules\site\Module;
use yii\captcha\CaptchaAction;
use yii\web\ErrorAction;
use yii\web\ViewAction;
use Yii;

/**
 * Frontend main controller.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className()
            ],
            'about' => [
                'class' => ViewAction::className(),
                'defaultView' => 'about',
                'viewPrefix' => '',
            ],
            'agreement' => [
                'class' => ViewAction::className(),
                'defaultView' => 'agreement',
                'viewPrefix' => '',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//                'backColor' => 0XF5F5F5,
//                'height' => 34,
                'backColor'=>0xE8E8E8, //цвет фона капчи
//                'testLimit'=>2, //сколько раз капча не меняется
                'transparent'=>false,
                'foreColor'=>0x200000, //цвет символов
            ]

        ];
    }

    /**
     * Site "Home" page.
     */
    public function actionIndex()
    {
        if(Yii::$app->getUser()->isGuest){
            return $this->redirect(['/signup/']);
        }
        elseif(Yii::$app->getUser()->role == 'superadmin'){
            return $this->redirect(['/backend/']);
        }
        else{
            return $this->redirect(['/'.Yii::$app->getUser()->role.'/contract/performer/list']);
        }
    }

    /**
     * Site "Contact" page.
     */
    public function actionFeedback()
    {
        $model = new ContactForm;
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash(
                'success',
                Module::t('site', 'FEEDBACK_FLASH_SUCCESS_MSG')
            );
            return $this->refresh();
        } else {
            return $this->render(
                'feedback',
                [
                    'model' => $model
                ]
            );
        }
    }
}
