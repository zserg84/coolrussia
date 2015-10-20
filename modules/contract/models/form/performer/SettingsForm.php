<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 17:17
 */

namespace modules\contract\models\form\performer;

use yii\base\Model;
use modules\themes\Module as ThemeModule;

class SettingsForm extends Model
{

    public $notification_of_new_orders;             //  Отправлять уведомления на E-mail о новых заказах в системе в соответствии с направлениями деятельности Вашей компании
    public $notification_of_orders_company_performer;   //  Отправлять уведомления на E-mail о заказах по которым Ваша компания выбрана исполнителем
    public $notification_of_permormer_response;     //  Отправлять уведомления на E-mail об откликах Исполнителей на заказ
    public $apply_filter_territory;                 //  Применить при входе в Систему территориальный фильтр в соответствии с моим территориальным месторасположением
    public $is_dop_regions;                         // Кроме региона по умолчанию, дополнительно отображать заказы по следующим регионам
    public $country;
    public $state;
    public $city;

    public function rules(){
        return [
            [['notification_of_permormer_response', 'apply_filter_territory', 'notification_of_new_orders' , 'notification_of_orders_company_performer', 'is_dop_regions',
            'country', 'state', 'city'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'notification_of_new_orders' => ThemeModule::t('FORM_PERFORMER_SETTINGS', 'PERFORMER_SETTING1'),
            'notification_of_orders_company_performer' => ThemeModule::t('FORM_PERFORMER_SETTINGS', 'PERFORMER_SETTING2'),
            'notification_of_permormer_response' => ThemeModule::t('FORM_PERFORMER_SETTINGS', 'PERFORMER_SETTING3'),
            'apply_filter_territory' => ThemeModule::t('FORM_PERFORMER_SETTINGS', 'PERFORMER_SETTING4'),
            'is_dop_regions' => ThemeModule::t('FORM_PERFORMER_SETTINGS', 'PERFORMER_SETTING5'),
            'country' => ThemeModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_COUNTRY'),
            'state' => ThemeModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_STATE'),
            'city' => ThemeModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_CITY'),
        ];
    }

    public function scenarios(){
        return [
            'ajax' => [
                'notification_of_new_orders', 'notification_of_orders_company_performer', 'notification_of_permormer_response', 'apply_filter_territory', 'is_dop_regions',
                'country', 'state', 'city',
            ],
            'default' => [
                'notification_of_new_orders', 'notification_of_orders_company_performer', 'notification_of_permormer_response', 'apply_filter_territory', 'is_dop_regions',
                'country', 'state', 'city',
            ]
        ];
    }
}