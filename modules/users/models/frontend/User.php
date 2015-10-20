<?php

/**
 * Class User
 * @package modules\users\models\frontend
 * User is the model behind the signup form.
 *
 * @property string $name Name
 * @property string $email E-mail
 * @property string $password Password
 * @property string $repassword Repeat password
 *
 */

namespace modules\users\models\frontend;

use modules\image\models\Image;
use modules\users\Module;
use Yii;
use kop\y2cv\ConditionalValidator;
use yii\helpers\VarDumper;

class User extends \modules\users\models\User
{
    /**
     * @var string $password Password
     */
    public $password;

    /**
     * @var string $repassword Repeat password
     */
    public $repassword;

    public $country_id;

    public $logo;

    private $_src_password = false;


    public function afterFind() {
        parent::afterFind();
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'login'], 'required'],
            [['name', 'email', 'password', 'repassword'], 'trim'],
            ['email', 'email'],
            [['email', 'login'], 'unique'],
            ['birthday', 'birthdayValidate'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            [['name', 'login'], 'string', 'min' => 2, 'max' => 64],
            ['email', 'string', 'max' => 100],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function birthdayValidate() {
        if ($this->birthday) {
            $date = new \DateTime($this->birthday);
            $this->birthday = $date->format('Y-m-d');
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['signup'] = ['name', 'email', 'password', 'repassword', 'country_id', 'state_id', 'city_id', 'image_id', 'login'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'password' => Module::t('users', 'ATTR_PASSWORD'),
            'repassword' => Module::t('users', 'ATTR_REPASSWORD'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setPassword($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $auth = Yii::$app->authManager;
            $roleName = $this->role ? $this->role : self::ROLE_DEFAULT;
//            $roleName = self::ROLE_DEFAULT;
            $role = $auth->getRole($roleName);
            $auth->assign($role, $this->id);

            if (Yii::$app->params['sendmail']) {
                if ((($this->module->requireEmailConfirmation === true) and ($this->status_id === User::STATUS_INACTIVE)) or ($this->_src_password)) {
                    $this->sendMail();
                }
            }

        }
    }

    public function passwordValidate() {
        if (!$this->use_password or (!$this->password and !$this->repassword)) {
            $this->_src_password = $this->password = $this->repassword = Yii::$app->security->generateRandomString(10);
        }
    }

    /**
     * Send an email confirmation token.
     *
     * @return boolean true if email was sent successfully
     */
    public function sendMail()
    {
        return $this->module->mail
                    ->compose('signup', ['model' => $this])
                    ->setTo($this->email)
                    ->setSubject(Module::t('users', 'EMAIL_SUBJECT_SIGNUP').' '.Yii::$app->name)
                    ->send();
    }

    public function saveLogo() {
        if (is_null($this->logo)) return;
        if (($tmpName = $this->logo->tempName) and ($ext = $this->logo->extension)) {
            if ($image = Image::GetByFile($tmpName, $ext)) {
                $this->image_id = $image->id;
                $this->save();
            }
        }
    }

    public function getSrcPassword() {
        return $this->_src_password;
    }

}
