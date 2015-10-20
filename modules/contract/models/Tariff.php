<?php

namespace modules\contract\models;

use Yii;

/**
 * This is the model class for table "tariff".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Contract[] $contracts
 */
class Tariff extends \yii\db\ActiveRecord
{
    const DEFAULT_TARIFF = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tariff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('tariff', 'ID'),
            'name' => Yii::t('tariff', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['tariff_id' => 'id']);
    }
}
