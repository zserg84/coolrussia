<?php

namespace modules\page\models;

use Yii;
use modules\lang\models\Lang;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property string $url
 * @property string $title
 * @property string $txt
 * @property integer $visibility
 * @property integer $create_time
 * @property integer $update_time
 *
 * @property Lang $lang
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'visibility', 'create_time', 'update_time'], 'integer'],
            [['txt'], 'string'],
            [['title', 'url'], 'required'],
            [['url', 'title'], 'string', 'max' => 255],
            ['url', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message'=>'может содержать только символы a-z, 0-9, «-», «_»'],
            [['lang_id', 'url'], 'unique', 'targetAttribute' => ['lang_id', 'url'], 'message' => 'The combination of Lang ID and Url has already been taken.'],
        ];
    }

    public function beforeValidate() {
        $this->update_time = time();
        if ($this->isNewRecord) {
            $this->create_time = time();
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('page', 'ID'),
//            'lang_id' => Yii::t('page', 'Lang ID'),
//            'url' => Yii::t('page', 'Url'),
//            'title' => Yii::t('page', 'Title'),
//            'txt' => Yii::t('page', 'Txt'),
//            'visibility' => Yii::t('page', 'Visibility'),
//            'create_time' => Yii::t('page', 'Create Time'),
//            'update_time' => Yii::t('page', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }


    public function getLangName() {
        return $this->getLang()->one()->name;
    }


    public function getLink() {
        $module = Yii::$app->getModule('page');
        return $module->link.$this->url.'/';
    }
}
