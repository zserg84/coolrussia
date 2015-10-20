<?php

namespace modules\users\models;

use kop\y2cv\ConditionalValidator;
use modules\image\models\Image;
use modules\users\Module;
use modules\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

class CustomerRegForm extends Model
{

    use ModuleTrait;

    public $login;
    public $password;
    public $repassword;
    public $name;
    public $email;
    public $phone_country_code;
    public $phone_city_code;
    public $phone_num;
    public $country;
    public $state;
    public $city;
    public $logo;
    public $confirm;
    public $captcha;
    public $role = 'customer';

    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['confirm', 'filter', 'filter' => function ($v) {
                return $v ? $v : null;
            }],
            [['name', 'email', 'password', 'repassword', 'login'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            [['name', 'login'], 'string', 'min' => 2, 'max' => 64],
            ['email', 'string', 'max' => 100],
            [['confirm'], 'required', 'message'=>'Необходимо согласиться с правилами использования сервиса'],
            [['captcha', 'login', 'password', 'repassword', 'name', 'email', 'phone_country_code', 'phone_city_code', 'phone_num', 'city'], 'required'],
            ['captcha', 'captcha', 'captchaAction'=>'/site/default/captcha'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['email', 'email'],
            [['role', 'login'], 'safe'],
            [['login', 'email'], 'unique', 'targetClass' => User::className()],
            [['phone_country_code', 'phone_city_code', 'phone_num'], 'number'],
            ['logo', 'file', 'mimeTypes'=> ['image/png', 'image/jpeg', 'image/gif'], 'wrongMimeType'=>Module::t('image', 'IMAGE_MESSAGE_FILE_TYPES').' jpg, png, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'registration_date' => Module::t('REGISTRATION_FORM_CUSTOMER', 'DATE_OF_REGISTRATION_CUSTOMER_REG_FORM'),
            'login' => Module::t('REGISTRATION_FORM_CUSTOMER', 'LOGIN_CUSTOMER_REG_FORM'),
            'password' => Module::t('REGISTRATION_FORM_CUSTOMER', 'PASSWORD_CUSTOMER_REG_FORM'),
            'repassword' => Module::t('REGISTRATION_FORM_CUSTOMER', 'PASSWORD2_CUSTOMER_REG_FORM'),
            'name' => Module::t('REGISTRATION_FORM_CUSTOMER', 'NAME_OR_COMPANY_NAME_CUSTOMER_REG_FORM'),
            'email' => Module::t('REGISTRATION_FORM_CUSTOMER', 'EMAIL_CUSTOMER_REG_FORM'),
            'phone_country_code' => Module::t('REGISTRATION_FORM_CUSTOMER', 'COUNTRY_PHONE_CODE1_CUSTOMER_REG_FORM'),
            'phone_city_code' => Module::t('REGISTRATION_FORM_CUSTOMER', 'CITY_PHONE_CODE1_CUSTOMER_REG_FORM'),
            'phone_num' => Module::t('REGISTRATION_FORM_CUSTOMER', 'PHONE_NUMBER1_CUSTOMER_REG_FORM'),
            'phone' => Module::t('REGISTRATION_FORM_CUSTOMER', 'PHONE1_CUSTOMER_REG_FORM'),
            'country' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_COUNTRY'),
            'state' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_STATE'),
            'city' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_CITY'),
            'logo' => Module::t('REGISTRATION_FORM_CUSTOMER', 'AVATAR_CUSTOMER_REG_FORM'),
            'confirm' => Module::t('REGISTRATION_FORM_CUSTOMER', 'LICENSE_AGREEMENT_CUSTOMER_REG_FORM'),
            'captcha' => Module::t('REGISTRATION_FORM_CUSTOMER', 'ENTER_SYMBOLS_FROM_THE_PICTURE_CUSTOMER_REG_FORM'),
            'customer_registration' => Module::t('REGISTRATION_FORM_CUSTOMER', 'NAME_OF_CUSTOMER_REG_FORM'),
            'cancel_button' => Module::t('REGISTRATION_FORM_CUSTOMER', 'CANCEL_BUTTON_CUSTOMER_REG_FORM'),
            'submit_button' => Module::t('REGISTRATION_FORM_CUSTOMER', 'REGISTRATION_BUTTON_CUSTOMER_REG_FORM'),
        ];
    }

    public function scenarios()
    {
        return [
            'ajax' => [
                'login', 'password', 'repassword', 'email', 'name',
                'phone_country_code','phone_city_code','phone_num',
                'country', 'state', 'city',
                'confirm',
            ],
            'default' => [
                'login', 'password', 'repassword', 'email', 'name',
                'phone_country_code','phone_city_code','phone_num',
                'country', 'state', 'city',
                'confirm', 'captcha',
            ]
        ];
    }

}