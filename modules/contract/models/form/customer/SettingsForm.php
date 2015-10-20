<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 17:17
 */

namespace modules\contract\models\form\customer;

use yii\base\Model;
use modules\themes\Module as ThemeModule;

class SettingsForm extends Model
{

    public $notification_of_permormer_response;     //  Отправлять уведомления на E-mail об откликах Исполнителей на заказ
    public $apply_filter_territory;                 //  Применить при входе в Систему территориальный фильтр в соответствии с моим территориальным месторасположением

    public function rules(){
        return [
            [['notification_of_permormer_response', 'apply_filter_territory'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'notification_of_permormer_response' => ThemeModule::t('FORM_CUSTOMER_SETTINGS', 'CUSTOMER_SETTING1'),
            'apply_filter_territory' => ThemeModule::t('FORM_CUSTOMER_SETTINGS', 'CUSTOMER_SETTING2'),
        ];
    }

    public function scenarios(){
        return [
            'ajax' => [
                'notification_of_permormer_response', 'apply_filter_territory',
            ],
            'default' => [
                'notification_of_permormer_response', 'apply_filter_territory',
            ]
        ];
    }
}