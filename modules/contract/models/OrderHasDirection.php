<?php

namespace modules\contract\models;

use modules\direction\models\Direction;
use Yii;

/**
 * This is the model class for table "order_has_direction".
 *
 * @property integer $order_id
 * @property integer $direction_id
 *
 * @property Direction $direction
 * @property Order $order
 */
class OrderHasDirection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_has_direction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'direction_id'], 'required'],
            [['order_id', 'direction_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('order_has_direction', 'Order ID'),
            'direction_id' => Yii::t('order_has_direction', 'Direction ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
