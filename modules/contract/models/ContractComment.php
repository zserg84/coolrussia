<?php

namespace modules\contract\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "contract_comment".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $comment_contract_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $text
 * @property integer $estimate
 *
 * @property Contract $contract
 * @property Contract $commentContract
 */
class ContractComment extends \yii\db\ActiveRecord
{
    const ESTIMATE_NEGATIVE = -1;
    const ESTIMATE_NEUTRAL = 0;
    const ESTIMATE_POSITIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'comment_contract_id'], 'required'],
            [['contract_id', 'comment_contract_id', 'estimate'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('contract_comment', 'ID'),
            'contract_id' => Yii::t('contract_comment', 'Contract ID'),
            'comment_contract_id' => Yii::t('contract_comment', 'Comment Contract ID'),
            'created_at' => Yii::t('contract_comment', 'created_at'),
            'text' => Yii::t('contract_comment', 'Text'),
            'estimate' => Yii::t('contract_comment', 'Estimate'),
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
    public function getCommentContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'comment_contract_id']);
    }

    public static  function estimateList(){
        return [
            self::ESTIMATE_POSITIVE => 'Положительный',
            self::ESTIMATE_NEUTRAL => 'Нейтральный',
            self::ESTIMATE_NEGATIVE => 'Негативный',
        ];
    }
}
