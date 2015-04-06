<?php

namespace modules\users\models;

use modules\users\Module;
use modules\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package modules\users\models
 * LoginForm is the model behind the login form.
 *
 * @property string $email Email
 * @property string $password Password
 * @property boolean $rememberMe Remember me
 */
class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $email Email
     */
    public $email;

    /**
     * @var string $password Password
     */
    public $password;

    /**
     * @var boolean rememberMe Remember me
     */
    public $rememberMe = true;

    /**
     * @var User|boolean User instance
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['email', 'password'], 'required'],
            // Password
            ['password', 'validatePassword'],
            // Remember Me
            ['rememberMe', 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('users', 'ATTR_EMAIL'),
            'password' => Module::t('users', 'ATTR_PASSWORD'),
            'rememberMe' => Module::t('users', 'ATTR_REMEMBER_ME')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Module::t('users', 'ERROR_MSG_INVALID_EMAIL_OR_PASSWORD'));
            }
        }
    }

    /**
     * Finds user by email.
     *
     * @return User|boolean User instance
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $user = User::findByEmail($this->email, 'active');
            if ($user !== null) {
                if ($this->module->isBackend) {
                    if (Yii::$app->authManager->checkAccess($user->id, 'accessBackend')) {
                        $this->_user = $user;
                    }
                } else {
                    $this->_user = $user;
                }
            }
        }
        return $this->_user;
    }

    /**
     * Logs in a user using the provided email and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
