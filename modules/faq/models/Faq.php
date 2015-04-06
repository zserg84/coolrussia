<?php

namespace modules\faq\models;

use modules\faq\Module;
use modules\lang\models\Lang;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $request
 * @property string $response
 * @property integer $status
 */
class Faq extends ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request', 'response'], 'required'],
            [['status'], 'integer'],
            [['request', 'response'], 'string', 'max' => 255],
            [['request'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'request' => Yii::t('app', 'Request'),
//            'response' => Yii::t('app', 'Response'),
//            'status' => Yii::t('app', 'Show status'),
        ];
    }

    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Module::t('faq', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Module::t('faq', 'STATUS_INACTIVE'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqLangs()
    {
        return $this->hasMany(FaqLang::className(), ['faq_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(!FaqLang::getFaqLangByFaqAndLang($this->id)->one()){
            $fl = new FaqLang();
            $fl->setAttributes($this->getAttributes());
            $fl->faq_id = $this->id;
            $fl->lang_id = Lang::getCurrent()->id;
            $fl->save();
        }
    }

    public function getFaqLangByLang($langId=null)
    {
        return FaqLang::getFaqLangByFaqAndLang($this->id, $langId)->one();
    }

    public function getRequest(){
        return $this->getFaqLangByLang() ? $this->getFaqLangByLang()->request : $this->request;
    }

    public function getResponse(){
        return $this->getFaqLangByLang()?  $this->getFaqLangByLang()->response : $this->response;
    }

}
