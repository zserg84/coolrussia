<?php

namespace modules\geo\models;

use modules\geo\Module;
use Yii;
use modules\geo\components\IPGeoBase;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%geo_country}}".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $code
 * @property integer $sort
 *
 * @property GeoCity[] $geoCities
 * @property GeoRegion[] $geoRegions
 */
class GeoCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en', 'code', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['name_ru', 'name_en'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('geo-country', 'ID'),
            'name_ru' => Module::t('geo-country', 'NAME_RU'),
            'name_en' => Module::t('geo-country', 'NAME_EN'),
            'code' => Module::t('geo-country', 'CODE'),
            'sort' => Module::t('geo-country', 'SORT'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCity::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegions()
    {
        return $this->hasMany(GeoRegion::className(), ['country_id' => 'id']);
    }


    /**
     * @param string $ip
     * @return |\yii\db\ActiveRecord
     */
    public static function GetByIp($ip) {
        if ($id = IpGeoBase::GetCountryByIP($ip)) {
            return GeoCountry::find()->where(['id'=>$id])->one();
        }
        return null;
    }
}
