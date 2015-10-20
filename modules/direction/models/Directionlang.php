<?php

namespace modules\direction\models;

use modules\lang\models\Lang;
use Yii;

/**
 * This is the model class for table "directionlang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $direction_id
 * @property string $translate
 *
 * @property Lang $lang
 * @property Direction $direction
 */
class Directionlang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'directionlang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'direction_id', 'translate'], 'required'],
            [['lang_id', 'direction_id'], 'integer'],
            [['translate'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('directionlang', 'ID'),
//            'lang_id' => Yii::t('directionlang', 'Lang ID'),
//            'direction_id' => Yii::t('directionlang', 'Direction ID'),
//            'translate' => Yii::t('directionlang', 'Translate'),
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
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }

    public static function getDirectionlangByDirectionAndLang($directionId, $langId=null){
        $langId = $langId ? $langId : Lang::getCurrent()->id;
        return self::find()->where('direction_id=:direction AND lang_id=:lang', [
            'direction'=>$directionId,
            'lang'=>$langId
        ]);
    }
}
