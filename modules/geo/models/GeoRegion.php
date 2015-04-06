<?php

namespace modules\geo\models;

use modules\geo\Module;
use Yii;

/**
 * This is the model class for table "{{%geo_region}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $name_ru
 * @property string $name_en
 * @property integer $sort
 *
 * @property GeoCity[] $geoCities
 * @property GeoCountry $country
 */
class GeoRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'name_ru', 'name_en', 'sort'], 'required'],
            [['country_id', 'sort'], 'integer'],
            [['name_ru', 'name_en'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('geo-region', 'ID'),
            'country_id' => Module::t('geo-region', 'COUNTRY_ID'),
            'name_ru' => Module::t('geo-region', 'NAME_RU'),
            'name_en' => Module::t('geo-region', 'NAME_EN'),
            'sort' => Module::t('geo-region', 'SORT'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCity::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'country_id']);
    }
}
