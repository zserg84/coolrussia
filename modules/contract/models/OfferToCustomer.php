<?php

namespace modules\contract\models;

use Yii;

/**
 * This is the model class for table "offer_to_customer".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $offer_to_performer_id
 * @property integer $order_id
 * @property string $description
 * @property string $price
 * @property integer $currency_id
 *
 * @property Currency $currency
 * @property Contract $contract
 * @property OfferToPerformer $offerToPerformer
 * @property Order $order
 */
class OfferToCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_to_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'order_id', 'price', 'currency_id'], 'required'],
            [['contract_id', 'offer_to_performer_id', 'order_id', 'currency_id', 'price'], 'integer'],
            [['price'], 'number'],
            [['description'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('offer_to_customer', 'ID'),
            'contract_id' => Yii::t('offer_to_customer', 'Contract ID'),
            'offer_to_performer_id' => Yii::t('offer_to_customer', 'Offer To Performer ID'),
            'order_id' => Yii::t('offer_to_customer', 'Order ID'),
            'description' => Yii::t('offer_to_customer', 'Description'),
            'price' => Yii::t('offer_to_customer', 'Price'),
            'currency_id' => Yii::t('offer_to_customer', 'Currency_id'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
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
    public function getOfferToPerformer()
    {
        return $this->hasOne(OfferToPerformer::className(), ['id' => 'offer_to_performer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['ajax'] = [
            'description', 'price', 'currency_id',
        ];
        return $scenarios;
    }
}
