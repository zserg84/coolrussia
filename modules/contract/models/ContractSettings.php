<?php

namespace modules\contract\models;

use Yii;

/**
 * This is the model class for table "contract_settings".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $notification_of_permormer_response
 * @property integer $apply_filter_territory
 * @property integer $notification_of_new_orders
 * @property integer $notification_of_orders_company_performer
 * @property integer $is_dop_regions
 *
 * @property Contract $contract
 * @property ContractSettingsRegion[] $contractSettingsRegions
 */
class ContractSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id'], 'required'],
            [['contract_id', 'notification_of_permormer_response', 'apply_filter_territory', 'notification_of_new_orders', 'notification_of_orders_company_performer', 'is_dop_regions'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('contract_settings', 'ID'),
            'contract_id' => Yii::t('contract_settings', 'Contract ID'),
            'notification_of_permormer_response' => Yii::t('contract_settings', 'Notification Of Permormer Response'),
            'apply_filter_territory' => Yii::t('contract_settings', 'Apply Filter Territory'),
            'notification_of_new_orders' => Yii::t('contract_settings', 'Notification Of New Orders'),
            'notification_of_orders_company_performer' => Yii::t('contract_settings', 'Notification Of Orders Company Performer'),
            'is_dop_regions' => Yii::t('contract_settings', 'Is Dop Regions'),
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
    public function getContractSettingsRegions()
    {
        return $this->hasMany(ContractSettingsRegion::className(), ['setting_id' => 'id']);
    }
}
