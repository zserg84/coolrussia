<?php

namespace modules\users\models;

use Yii;
use modules\users\Module;
use modules\users\models\User;
use modules\geo\models\GeoCity;
use modules\lang\models\Lang;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $lang_id
 * @property string $name
 * @property string $about
 * @property string $video
 *
 * @property Lang $lang
 * @property Users $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'lang_id', 'name'], 'required'],
            [['user_id', 'lang_id'], 'integer'],
            [['about'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['video'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('users', 'ID'),
            'user_id' => Yii::t('users', 'User ID'),
            'lang_id' => Yii::t('users', 'Lang ID'),
            'name' => Yii::t('users', 'Name'),
            'about' => Yii::t('users', 'About'),
            'video' => Yii::t('users', 'Video'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function getFullName() {
        return $this->name;
    }

}
