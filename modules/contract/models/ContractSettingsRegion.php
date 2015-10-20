<?php

namespace modules\contract\models;

use common\models\City;
use common\models\Country;
use common\models\State;
use Yii;

/**
 * This is the model class for table "contract_settings_region".
 *
 * @property integer $id
 * @property integer $setting_id
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $city_id
 *
 * @property City $city
 * @property Country $country
 * @property ContractSettings $setting
 * @property State $state
 */
class ContractSettingsRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_settings_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_id'], 'required'],
            [['setting_id', 'country_id', 'state_id', 'city_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('contract_settings_region', 'ID'),
            'setting_id' => Yii::t('contract_settings_region', 'Setting ID'),
            'country_id' => Yii::t('contract_settings_region', 'Country ID'),
            'state_id' => Yii::t('contract_settings_region', 'State ID'),
            'city_id' => Yii::t('contract_settings_region', 'City ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(ContractSettings::className(), ['id' => 'setting_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }
}
