<?php

namespace modules\users\models\frontend;

use modules\users\models\User;
use modules\users\Module;
use modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class PasswordForm
 * @package modules\users\models
 * PasswordForm is the model behind the change password form.
 *
 * @property string $password Password
 * @property string $repassword Repeat password
 * @property string $oldpassword Current password
 */
class PasswordForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $password Password
     */
    public $password;

    /**
     * @var string $repassword Repeat password
     */
    public $repassword;

    /**
     * @var string Current password
     */
    public $oldpassword;

    /**
     * @var \modules\users\models\User User instance
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['password', 'repassword', 'oldpassword'], 'required'],
            // Trim
            [['password', 'repassword', 'oldpassword'], 'trim'],
            // String
            [['password', 'repassword', 'oldpassword'], 'string', 'min' => 6, 'max' => 30],
            // Password
            ['password', 'compare', 'compareAttribute' => 'oldpassword', 'operator' => '!=='],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            // Oldpassword
            ['oldpassword', 'validateOldPassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Module::t('users', 'ATTR_NEW_PASSWORD'),
            'repassword' => Module::t('users', 'ATTR_NEW_REPASSWORD'),
            'oldpassword' => Module::t('users', 'ATTR_OLDPASSWORD')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validateOldPassword($attribute, $params)
    {
        $user = $this->user;
        if (!$user || !$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Module::t('users', 'ERROR_MSG_INVALID_OLD_PASSWORD'));
        }
    }

    /**
     * Change user password.
     *
     * @return boolean true if password was successfully changed
     */
    public function password()
    {
        if (($model = $this->user) !== null) {
            return $model->password($this->password);
        }
        return false;
    }

    /**
     * Finds user by id.
     *
     * @return User|null User instance
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['id' => Yii::$app->user->identity->id])->active()->one();
        }
        return $this->_user;
    }
}
