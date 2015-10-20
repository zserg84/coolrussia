<?php

namespace modules\users\models\frontend;

use modules\users\helpers\Security;
use modules\users\models\User;
use modules\users\Module;
use modules\themes\Module as ThemeModule;
use modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class RecoveryConfirmationForm
 * @package modules\users\models
 * RecoveryConfirmationForm is the model behind the recovery confirmation form.
 *
 * @property string $password Password
 * @property string $repassword Repeat password
 * @property string $token Secure token
 */
class RecoveryConfirmationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Repeat password
     */
    public $repassword;

    /**
     * @var string Confirmation token
     */
    public $token;

    /**
     * @var \modules\users\models\frontend\User User instance
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['password', 'repassword', 'token'], 'required'],
            // Trim
            [['password', 'repassword', 'token'], 'trim'],
            // String
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            ['token', 'string', 'max' => 53],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            // Secure token
            [
                'token',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->active();
                    }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => ThemeModule::t('Login form for ALL pages', 'PASSWORD_LOGIN_FORM_ALL_PAGES'),
            'repassword' => ThemeModule::t('Login form for ALL pages', 'REPASSWORD_LOGIN_FORM_ALL_PAGES')
        ];
    }

    /**
     * Check if token is valid.
     *
     * @return boolean true if token is valid
     */
    public function isValidToken()
    {
        if (Security::isValidToken($this->token, $this->module->recoveryWithin) === true) {
            return ($this->_user = User::findByToken($this->token, 'active')) !== null;
        }
        return false;
    }

    /**
     * Recover password.
     *
     * @return boolean true if password was successfully recovered
     */
    public function recovery()
    {
        $model = $this->_user;
        if ($model !== null) {
            return $model->recovery($this->password);
        }
        return false;
    }

    public function login()
    {
        return Yii::$app->user->login($this->_user, 0);
    }
}
