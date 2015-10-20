<?php

namespace modules\contract\models;

use Yii;

/**
 * This is the model class for table "offer_to_performer".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $order_id
 *
 * @property OfferToCustomer[] $offerToCustomers
 * @property Contract $contract
 * @property Order $order
 */
class OfferToPerformer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_to_performer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'order_id'], 'required'],
            [['contract_id', 'order_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('offer_to_performer', 'ID'),
            'contract_id' => Yii::t('offer_to_performer', 'Contract ID'),
            'order_id' => Yii::t('offer_to_performer', 'Order ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferToCustomers()
    {
        return $this->hasMany(OfferToCustomer::className(), ['offer_to_performer_id' => 'id']);
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
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
