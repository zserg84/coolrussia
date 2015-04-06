<?php

namespace modules\faq\models;

use modules\faq\models\Faq;
use modules\lang\models\Lang;
use Yii;

/**
 * This is the model class for table "faq_lang".
 *
 * @property integer $id
 * @property integer $faq_id
 * @property integer $lang_id
 *
 * @property Lang $lang
 * @property Faq $faq
 */
class FaqLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faq_id', 'lang_id', 'request', 'response'], 'required'],
            [['faq_id', 'lang_id'], 'integer'],
            [['request', 'response'], 'string', 'max' => 255],
            [['faq_id', 'lang_id'], 'unique', 'targetAttribute' => ['faq_id', 'lang_id'], 'message' => 'The combination of Faq ID and Lang ID has already been taken.'],
            [['faq_id', 'lang_id', 'request'], 'unique', 'targetAttribute' => ['faq_id', 'lang_id', 'request'], 'message' => 'The combination of Faq ID, Lang ID and Request has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'faq_id' => Yii::t('app', 'Faq ID'),
//            'lang_id' => Yii::t('app', 'Lang ID'),
//            'request' => Yii::t('app', 'Request'),
//            'response' => Yii::t('app', 'Response'),
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
    public function getFaq()
    {
        return $this->hasOne(Faq::className(), ['id' => 'faq_id']);
    }

    public static function getFaqLangByFaqAndLang($faqId, $langId=null){
        $langId = $langId ? $langId : Lang::getCurrent()->id;
        return FaqLang::find()->where('faq_id=:faq AND lang_id=:lang', [
            'faq'=>$faqId,
            'lang'=>$langId
        ]);
    }
}