<?php

namespace modules\contract\models;

use modules\direction\models\Direction;
use Yii;

/**
 * This is the model class for table "contract_performer_has_direction".
 *
 * @property integer $direction_id
 * @property integer $contract_id
 * @property string $equipment_manufacturer
 * @property string $equipment_model
 * @property integer $equipment_field
 * @property integer $equipment_year
 *
 * @property Contract $contract
 * @property Direction $direction
 */
class ContractPerformerHasDirection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_performer_has_direction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['direction_id', 'contract_id'], 'required'],
            [['direction_id', 'contract_id', 'equipment_field', 'equipment_year'], 'integer'],
            [['equipment_manufacturer', 'equipment_model'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'direction_id' => Yii::t('contract_performer_has_direction', 'Direction ID'),
            'contract_id' => Yii::t('contract_performer_has_direction', 'Contract ID'),
            'equipment_manufacturer' => Yii::t('contract_performer_has_direction', 'Equipment Manufacturer'),
            'equipment_model' => Yii::t('contract_performer_has_direction', 'Equipment Model'),
            'equipment_field' => Yii::t('contract_performer_has_direction', 'Equipment Field'),
            'equipment_year' => Yii::t('contract_performer_has_direction', 'Equipment Year'),
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
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }
}
