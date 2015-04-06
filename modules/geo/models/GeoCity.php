<?php

namespace modules\geo\models;

use Yii;
use modules\geo\components\IPGeoBase;
use modules\geo\Module;


/**
 * This is the model class for table "{{%geo_city}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $name_ru
 * @property string $name_en
 * @property integer $sort
 * @property integer $confirm
 *
 * @property GeoCountry $country
 * @property GeoRegion $region
 * @property Users[] $users
 */
class GeoCity extends \yii\db\ActiveRecord
{

    const CONFIRM_ACTIVE = 1;
    const CONFIRM_INACTIVE = 0;

    public static $patternName = '/^[a-zа-яё\- ]+$/iu';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'name_ru', 'name_en'], 'required'],
            [['country_id', 'region_id', 'sort', 'confirm'], 'integer'],
            [['name_ru', 'name_en'], 'match', 'pattern' => self::$patternName],
            [['name_ru', 'name_en'], 'string', 'min'=>2, 'max' => 50]
        ];
    }


    public function beforeSave($insert) {
        if($insert){
            if (!$this->sort) {
                $this->sort = 0;
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('geo-city', 'ID'),
            'country_id' => Yii::t('geo', 'COUNTRY_ID'),
            'region_id' => Yii::t('geo', 'REGION_ID'),
            'name_ru' => Yii::t('geo', 'NAME_RU'),
            'name_en' => Yii::t('geo', 'NAME_EN'),
            'sort' => Yii::t('geo', 'SORT'),
            'confirm' => Yii::t('geo', 'CONFIRM'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['city_id' => 'id']);
    }


    public static function findByName($name, $country_id = null) {
        $query = self::find()->where('name_ru = :name OR name_en = :name', [':name'=>$name]);
        if ($country_id = intval($country_id)) {
            $query->andWhere('country_id = :country_id', [':country_id'=>$country_id]);
        }
        return $query->one();
    }


    /**
     * @return string
     */
    public function getName($showRegion = false, $showCountry = false) {
        $lang = mb_substr(Yii::$app->language, 0, 2, 'utf8');
        if (isset($this->attributes['name_'.$lang])) {
            $output = $this->attributes['name_'.$lang];
        } else {
            $output = Yii::t('geo', $this->name_en);
        }

        if ($showRegion and $showCountry) {
            $output .= ' (';
            if ($this->region_id) {
                $output .= $this->region->name_ru.', ';
            }
            $output .= $this->country->name_ru.')';
        } elseif ($showCountry) {
            $output .= ' ('.$this->country->name_ru.')';
        } elseif ($showRegion and $this->region_id) {
            $output .= ' ('.$this->region->name_ru.')';
        }
        return $output;
    }


    /**
     * @param int $id
     * @param int $country_id
     * @param string $name
     * @return int|null
     * @comment Ищем город по ID и Стране и сравниваем Название. Если не нашли, создаём новый город.
     */
    public static function GetOrCreate($id, $country_id, $name) {
        if (!$name = trim($name)) {
            return null;
        }
        $country_id = intval($country_id);
        $id = intval($id);
        if ($id and ($geoCity = GeoCity::find()->where('id = :id AND country_id = :country_id', [':id' => $id, ':country_id' => $country_id])->one())) {
            if (($geoCity->name_ru !== $name) and ($geoCity->name_en !== $name)) {
                $id = null;
            } else {
                return $geoCity->id;
            }
        }
        if (!$id and $name) {
            if ($geoCity = GeoCity::findByName($name, $country_id)) {
                return $geoCity->id;
            } else {
                $geoCity = new GeoCity();
                $geoCity->country_id = $country_id;
                $geoCity->name_ru = $name;
                $geoCity->name_en = $name;
                $geoCity->confirm = 0;
                if ($geoCity->save()) {
                    return $geoCity->id;
                }
            }
        }
        return null;
    }

    /**
     * @param string $ip
     * @return GeoCity|null
     */
    public static function getByIP($ip) {
        $gb = new IpGeoBase();
        $res = $gb->getRecord($ip);
        $geoCity = null;
        if (is_array($res) and isset($res['cc']) and isset($res['city'])) {
            foreach ($res as $k => $val) {
                $res[$k] = mb_convert_encoding($val, 'utf-8', 'windows-1251');
            }
            $country_code = $res['cc'];
            $city_name = $res['city'];
            if ($geoCountry = GeoCountry::find()->where('code = :code', [':code'=>$country_code])->one()) {
                $geoCity = GeoCity::find()->where('country_id = :country_id AND name_ru = :name', [':country_id'=>$geoCountry->id, ':name'=>$city_name])->one();
            }
        }
        return $geoCity;
    }

    public static function getConfirmArray()
    {
        return [
            self::CONFIRM_ACTIVE => Module::t('geo', 'CITY_CONFIRM_ACTIVE'),
            self::CONFIRM_INACTIVE => Module::t('geo', 'CITY_CONFIRM_INACTIVE'),
        ];
    }
}