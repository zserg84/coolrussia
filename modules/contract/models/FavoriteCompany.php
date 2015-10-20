<?php

namespace modules\contract\models;

use Yii;

/**
 * This is the model class for table "favorite_company".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $favorite_contract_id
 *
 * @property Contract $contract
 * @property Contract $favoriteContract
 */
class FavoriteCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorite_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'favorite_contract_id'], 'required'],
            [['contract_id', 'favorite_contract_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('favorite_company', 'ID'),
            'contract_id' => Yii::t('favorite_company', 'Contract ID'),
            'favorite_contract_id' => Yii::t('favorite_company', 'Favorite Contract ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoriteContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'favorite_contract_id']);
    }
}
