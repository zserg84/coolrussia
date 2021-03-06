<?php

namespace modules\users\models;

use modules\users\models\frontend\User;
use modules\users\Module;
use modules\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

class PerformerRegForm extends Model
{

    use ModuleTrait;

    public $login;
    public $password;
    public $repassword;
    public $email;
    public $email_for_order;
    public $company_name;
    public $company_about;
    public $company_specialization;
    public $company_count_years;
    public $site;
    public $phone_country_code_1;
    public $phone_city_code_1;
    public $phone_num_1;
    public $phone_country_code_2;
    public $phone_city_code_2;
    public $phone_num_2;
    public $country;
    public $state;
    public $city;
    public $street;
    public $house;
    public $corpus;
    public $building;
    public $logo;
    public $examples_of_works = [];
    public $additional_info;
    public $confirm;
    public $captcha;
    public $role = 'performer';


    public function rules()
    {
        return [
            ['confirm', 'filter', 'filter' => function ($v) {
                return $v ? $v : null;
            }],
            [['company_name', 'email', 'password', 'repassword', 'login'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            [['company_name', 'login'], 'string', 'min' => 2, 'max' => 64],
            [['email'], 'string', 'max' => 100],
            [['confirm'], 'required', 'message'=>'Необходимо согласиться с правилами использования сервиса'],
            [['captcha', 'login', 'password', 'repassword', 'company_name', 'email', 'site',
                'phone_country_code_1', 'phone_city_code_1', 'phone_num_1',
                'phone_country_code_2', 'phone_city_code_2', 'phone_num_2',
                'city', 'street', 'email_for_order', 'company_specialization', 'company_count_years', 'company_about', 'additional_info',
            ], 'required'],
            [['house', 'building', 'company_count_years'], 'number'],
            [['corpus'], 'string', 'max' => 5],
            ['captcha', 'captcha', 'captchaAction'=>'/site/default/captcha'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            [['email', 'email_for_order'], 'email'],
            [['login', 'email'], 'unique', 'targetClass' => User::className()],
            [['phone_country_code_1', 'phone_city_code_1', 'phone_num_1', 'phone_country_code_2', 'phone_city_code_2', 'phone_num_2'], 'number'],
            [['role', 'login', 'examples_of_works', 'additional_info', 'company_count_years', 'company_about'], 'safe'],
            [['logo'], 'file', 'mimeTypes'=> ['image/png', 'image/jpeg', 'image/gif'], 'wrongMimeType'=>Module::t('image', 'IMAGE_MESSAGE_FILE_TYPES').' jpg, png, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'performer_registration' => Module::t('REGISTRATION_FORM_PERFORMER', 'NAME_OF_PERFORMER_REG_FORM'),
            'registration_date' => Module::t('REGISTRATION_FORM_PERFORMER', 'DATE_OF_REGISTRATION_PERFORMER_REG_FORM'),
            'login' => Module::t('REGISTRATION_FORM_PERFORMER', 'LOGIN_PERFORMER_REG_FORM'),
            'password' => Module::t('REGISTRATION_FORM_PERFORMER', 'PASSWORD1_PERFORMER_REG_FORM'),
            'repassword' => Module::t('REGISTRATION_FORM_PERFORMER', 'PASSWORD2_PERFORMER_REG_FORM'),
            'company_name' => Module::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_NAME_PERFORMER_REG_FORM'),
            'company_about' => Module::t('REGISTRATION_FORM_PERFORMER', 'ABOUT_COMPANY_PERFORMER_REG_FORM'),
            'company_specialization' => Module::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_SPECIALIZATION_PERFORMER_REG_FORM'),
            'company_count_years' => Module::t('REGISTRATION_FORM_PERFORMER', 'YEARS_IN_THE_MARKET_PERFORMER_REG_FORM'),
            'site' => Module::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_SITE_PERFORMER_REG_FORM'),
            'email' => Module::t('REGISTRATION_FORM_PERFORMER', 'EMAIL_PERFORMER_REG_FORM'),
            'email_for_order' => Module::t('REGISTRATION_FORM_PERFORMER', 'EMAIL_FOR_OBTAINING_ORDERS_REG_FORM'),
            'phone_country_code_1' => Module::t('REGISTRATION_FORM_PERFORMER', 'COUNTRY_PHONE_CODE1_PERFORMER_REG_FORM'),
            'phone_city_code_1' => Module::t('REGISTRATION_FORM_PERFORMER', 'CITY_PHONE_CODE1_PERFORMER_REG_FORM'),
            'phone_num_1' => Module::t('REGISTRATION_FORM_PERFORMER', 'PHONE_NUMBER1_PERFORMER_REG_FORM'),
            'phone_1' => Module::t('REGISTRATION_FORM_PERFORMER', 'PHONE1_PERFORMER_REG_FORM'),
            'phone_country_code_2' => Module::t('REGISTRATION_FORM_PERFORMER', 'COUNTRY_PHONE_CODE2_PERFORMER_REG_FORM'),
            'phone_city_code_2' => Module::t('REGISTRATION_FORM_PERFORMER', 'CITY_PHONE_CODE2_PERFORMER_REG_FORM'),
            'phone_num_2' => Module::t('REGISTRATION_FORM_PERFORMER', 'PHONE_NUMBER2_PERFORMER_REG_FORM'),
            'phone_2' => Module::t('REGISTRATION_FORM_PERFORMER', 'PHONE2_PERFORMER_REG_FORM'),
            'country' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_COUNTRY'),
            'state' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_STATE'),
            'city' => Module::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_CITY'),
            'street' => Module::t('REGISTRATION_FORM_PERFORMER', 'STREET_PERFORMER_REG_FORM'),
            'house' => Module::t('REGISTRATION_FORM_PERFORMER', 'HOUSE_PERFORMER_REG_FORM'),
            'building' => Module::t('REGISTRATION_FORM_PERFORMER', 'BUILDING_PERFORMER_REG_FORM'),
            'corpus' => Module::t('REGISTRATION_FORM_PERFORMER', 'STRUCTURE_PERFORMER_REG_FORM'),
            'logo' => Module::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_LOGO_PERFORMER_REG_FORM'),
            'confirm' => Module::t('REGISTRATION_FORM_PERFORMER', 'LICENSE_AGREEMENT_PERFORMER_REG_FORM'),
            'captcha' => Module::t('REGISTRATION_FORM_PERFORMER', 'ENTER_SYMBOLS_FROM_THE_PICTURE_PERFORMER_REG_FORM'),
            'directions' => Module::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_ACTIVITIES_PERFORMER_REG_FORM'),
            'examples_of_works' => Module::t('REGISTRATION_FORM_PERFORMER', 'LOAD_FOTO_EXAMPLES_OF_WORKS_PERFOMER_REG_FORM'),
            'additional_info' => Module::t('REGISTRATION_FORM_PERFORMER', 'ADDITIONAL_INFO'),
            'customer_registration' => Module::t('REGISTRATION_FORM_PERFORMER', 'NAME_OF_PERFORMER_REG_FORM'),
            'cancel_button' => Module::t('REGISTRATION_FORM_PERFORMER', 'CANCEL_BUTTON_PERFORMER_REG_FORM'),
            'submit_button' => Module::t('REGISTRATION_FORM_PERFORMER', 'REGISTRATION_BUTTON_PERFORMER_REG_FORM'),
        ];
    }

    public function scenarios()
    {
        return [
            'ajax' => [
                'login', 'password', 'repassword', 'email', 'email_for_order',
                'company_name', 'company_about','company_specialization','company_count_years','site',
                'phone_country_code_1','phone_city_code_1','phone_num_1','phone_country_code_2','phone_city_code_2','phone_num_2',
                'country', 'state', 'city', 'street', 'house', 'corpus', 'building',
                'examples_of_works',
                'additional_info',
                'confirm',
//                'captcha',
            ],
            'default' => [
                'login', 'password', 'repassword', 'email', 'email_for_order',
                'company_name', 'company_about','company_specialization','company_count_years','site',
                'phone_country_code_1','phone_city_code_1','phone_num_1','phone_country_code_2','phone_city_code_2','phone_num_2',
                'country', 'state', 'city', 'street', 'house', 'corpus', 'building',
                'examples_of_works',
                'additional_info',
                'confirm',
                'captcha',
            ]
        ];
    }
}