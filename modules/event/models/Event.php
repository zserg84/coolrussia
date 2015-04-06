<?php

namespace modules\event\models;

use modules\image\models\Image;
use Yii;
use modules\event\Module;
use modules\event\models\query\EventQuery;
use modules\lang\models\Lang;
use modules\users\models\User;
use modules\geo\models\GeoCity;
use \yii\helpers\Url;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $duration_type
 * @property integer $duration
 * @property string $address
 * @property string $address_comment
 * @property string $time_type
 * @property string $cost_type
 * @property integer $prepayment
 * @property string $date_comment
 * @property integer $city_id
 * @property integer $status
 * @property integer $image_id
 * @property integer $recommended
 *
 * @property GeoCity $city
 * @property Image $image
 * @property Users $user
 * @property EventCategoryEvent[] $eventCategoryEvents
 * @property EventCategory[] $categories
 * @property EventDate[] $eventDates
 * @property EventImage[] $eventImages
 * @property EventLang[] $eventLangs
 * @property EventTimeRepeat[] $eventTimeRepeats
 */
class Event extends \yii\db\ActiveRecord {

    /** Inactive status */
    const STATUS_HIDDEN = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;
    /** Banned status */
    const STATUS_BANNED = 2;

    const DEFAULT_COVER_SRC = '';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'email', 'phone', 'duration_type', 'duration', 'time_type', 'cost_type'], 'required'],
            [['user_id', 'duration', 'prepayment', 'city_id', 'status', 'image_id', 'recommended'], 'integer'],
            [['duration', 'prepayment', 'status', 'city_id'], 'integer'],
            [['title', 'address', 'address_comment', 'date_comment'], 'string', 'max' => 255],
            [['email', 'phone'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('event', 'ID'),
            'user_id' => Module::t('event', 'User'),
            'title' => Module::t('event', 'Title'),
            'email' => Module::t('event', 'Email'),
            'phone' => Module::t('event', 'Phone'),
            'duration_type' => Module::t('event', 'Duration Type'),
            'duration' => Module::t('event', 'Duration'),
            'address' => Module::t('event', 'Адрес в формате «город, улица, дом»'),
            'address_comment' => Module::t('event', 'Коммент к адресу в свободной форме'),
            'time_type' => Module::t('event', 'Time Type'),
            'cost_type' => Module::t('event', 'Cost Type'),
            'prepayment' => Module::t('event', 'Предоплата'),
            'date_comment' => Module::t('event', 'Комментарий к дате события, если выбрана свободная дата'),
            'city_id' => Module::t('event', 'Город'),
            'status' => Module::t('event', 'Статус'),
            'image_id' => Module::t('event', 'Image'),
            'recommended' => Yii::t('app', 'Рекомендуемые'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventImages()
    {
        return $this->hasMany(EventImage::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCategoryEvents()
    {
        return $this->hasMany(EventCategoryEvent::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(EventCategory::className(), ['id' => 'category_id'])->viaTable(EventCategoryEvent::tableName(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventDates()
    {
        return $this->hasMany(EventDate::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLangs()
    {
        return $this->hasMany(EventLang::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTimeRepeats()
    {
        return $this->hasMany(EventTimeRepeat::className(), ['event_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $this->user_id = Yii::$app->getUser()->getId();
            $eventLang = new EventLang();
            $eventLang->event_id = $this->id;
            $eventLang->lang_id = Lang::getCurrent()->id;
            $eventLang->setAttributes($this->getAttributes());
            $eventLang->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }


    public function getViewUrl() {
        if (!$this->id) return false;
        return Url::toRoute(['/event/default/view/', 'id'=>$this->id]);
    }

    public function getEditUrl() {
        if (!$this->id) return false;
        return Url::toRoute(['/event/default/edit/', 'id'=>$this->id]);
    }

    /*
     * Обложка события
     * */
    public function getCover($w=null) {
        if ($this->image_id) {
            return $this->image->getSrc($w);
        }
        return self::DEFAULT_COVER_SRC;
    }

    public function getEventLangByLang($langId=null)
    {
        return EventLang::getEventLangByEventAndLang($this->id, $langId)->one();
    }

    public function getTitle(){
        $el = $this->getEventLangByLang();
        return $el ? $el->title : $this->title;
    }
}
