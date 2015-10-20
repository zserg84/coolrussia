<?php

namespace modules\contract\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order_comment".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $contract_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $text
 *
 * @property Contract $contract
 * @property Order $order
 */
class OrderComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'contract_id'], 'required'],
            [['order_id', 'contract_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['text'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('order_comment', 'ID'),
            'order_id' => Yii::t('order_comment', 'Order ID'),
            'contract_id' => Yii::t('order_comment', 'Contract ID'),
            'created_at' => Yii::t('order_comment', 'created_at'),
            'updated_at' => Yii::t('order_comment', 'updated_at'),
            'text' => Yii::t('order_comment', 'Text'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ]
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
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
