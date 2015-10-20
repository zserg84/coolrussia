<?php

namespace common\models;

use modules\image\models\Image;
use Yii;

/**
 * This is the model class for table "company_image".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $image_id
 *
 * @property Company $company
 * @property Image $image
 */
class CompanyImage extends \yii\db\ActiveRecord
{
    const DEFAULT_COVER_SRC = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'image_id'], 'required'],
            [['company_id', 'image_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('m_company_image', 'ID'),
            'company_id' => Yii::t('m_company_image', 'Company ID'),
            'image_id' => Yii::t('m_company_image', 'Image ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getLogo($w=null) {
        if ($this->image_id) {
            return $this->image->getSrc($w);
        }
        return self::DEFAULT_COVER_SRC;
    }
}
