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

use modules\geo\models\GeoCity;
use modules\geo\models\GeoCountry;
use modules\users\Module;
use Yii;
use kop\y2cv\ConditionalValidator;

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

    public $city_name;

    public $use_password = 0;

    public $use_avatar = 0;

    private $_src_password = false;


    public function afterFind() {
        parent::afterFind();
        if ($this->city_id) {
            $this->city_name = $this->city->getName();
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['name', 'email', 'password', 'repassword', 'city_name'], 'trim'],
            ['email', 'email'],
            [['email'], 'unique'],
            ['birthday', 'birthdayValidate'],
            [['country_id, city_id'], 'integer'],
            ['country_id', 'exist', 'targetClass'=>GeoCountry::className(), 'targetAttribute'=>'id'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            ['name', 'match', 'pattern' => Module::getInstance()->patternName],
            ['name', 'string', 'min' => 2, 'max' => 64],
            ['email', 'string', 'max' => 100],
            ['city_id', 'cityValidate', 'on'=>'signup'],
//            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['password', 'passwordValidate'],
            ['repassword', ConditionalValidator::className(),
                'if' => [
                    ['use_password', 'compare', 'compareValue' => 1]
                ],
                'then' => [
                    ['repassword', 'compare', 'compareAttribute' => 'password'],
                ]
            ],
        ];
    }

    public function birthdayValidate() {
        if ($this->birthday) {
            $date = new \DateTime($this->birthday);
            $this->birthday = $date->format('Y-m-d');
        }
    }

    public function passwordValidate() {
        if (!$this->use_password or (!$this->password and !$this->repassword)) {
            $this->_src_password = $this->password = $this->repassword = Yii::$app->security->generateRandomString(10);
        }
    }

    public function cityValidate() {
        $this->city_id = GeoCity::GetOrCreate($this->city_id, $this->country_id, $this->city_name);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['name', 'email', 'password', 'repassword', 'birthday', 'city_id', 'city_name', 'country_id', 'use_avatar', 'use_password', 'image_id'],
        ];
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
            'city_name' => Module::t('users', 'ATTR_CITY_NAME'),
            'country_id' => Module::t('users', 'ATTR_COUNTRY'),
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
                $this->status_id = self::STATUS_ACTIVE;
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
            if ($this->profile !== null) {
                $this->profile->save(false);
            }

            $auth = Yii::$app->authManager;
            $role = $auth->getRole(self::ROLE_DEFAULT);
            $auth->assign($role, $this->id);

            if (Yii::$app->params['sendmail']) {
                if ((($this->module->requireEmailConfirmation === true) and ($this->status_id === User::STATUS_INACTIVE)) or ($this->_src_password)) {
                    $this->sendMail();
                }
            }

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


    public function getSrcPassword() {
        return $this->_src_password;
    }

}
