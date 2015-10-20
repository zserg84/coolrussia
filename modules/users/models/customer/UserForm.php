<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 06.05.15
 * Time: 12:49
 */

namespace modules\users\models\customer;

use modules\themes\Module;
use modules\users\traits\ModuleTrait;
use yii\base\Model;

class UserForm extends Model
{

    use ModuleTrait;

    public $login;
    public $name;
    public $email;
    public $phone_country_code;
    public $phone_city_code;
    public $phone_num;
    public $country;
    public $state;
    public $city;
    public $logo;

    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'login'], 'trim'],
            [['name', 'login'], 'string', 'min' => 2, 'max' => 64],
            ['email', 'string', 'max' => 100],
            [['login', 'name', 'email', 'phone_country_code', 'phone_city_code', 'phone_num', 'country', 'state', 'city'], 'required'],
            ['email', 'email'],
            [['role', 'login'], 'safe'],
            [['login', 'email'], 'userUnique'],
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
            'customer_registration' => Module::t('REGISTRATION_FORM_CUSTOMER', 'NAME_OF_CUSTOMER_REG_FORM'),
            'cancel_button' => Module::t('REGISTRATION_FORM_CUSTOMER', 'CANCEL_BUTTON_CUSTOMER_REG_FORM'),
            'submit_button' => Module::t('REGISTRATION_FORM_CUSTOMER', 'REGISTRATION_BUTTON_CUSTOMER_REG_FORM'),
        ];
    }

    public function userUnique($attribute){
        if(!$this->user->validate([$attribute])){
            $this->addError($attribute, $this->user->getFirstError($attribute));
            return false;
        }
        return true;
    }

    public function scenarios()
    {
        return [
            'ajax' => [
                'login', 'email', 'name',
                'phone_country_code','phone_city_code','phone_num',
                'country', 'state', 'city',
            ],
            'default' => [
                'login', 'email', 'name',
                'phone_country_code','phone_city_code','phone_num',
                'country', 'state', 'city',
            ]
        ];
    }
} 