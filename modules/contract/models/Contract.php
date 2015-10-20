<?php

namespace modules\contract\models;

use common\models\City;
use common\models\Company;
use modules\direction\models\Direction;
use modules\contract\models\query\ContractQuery;
use modules\contract\Module as ContractModule;
use modules\users\models\User;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "contract".
 *
 * @property integer $id
 * @property integer $tariff_id
 * @property integer $city_id
 * @property string $name
 * @property string $type
 * @property string $street
 * @property string $house
 * @property string $corpus
 * @property string $building
 * @property integer $phone_country_code_1
 * @property integer $phone_city_code_1
 * @property integer $phone_num_1
 * @property integer $phone_country_code_2
 * @property integer $phone_city_code_2
 * @property integer $phone_num_2
 * @property integer $performer_id
 * @property integer $customer_id
 *
 * @property Company $performer
 * @property User $customer
 * @property City $city
 * @property Tariff $tariff
 * @property ContractComment[] $contractComments
 * @property Company[] $companies
 * @property ContractPerformerHasDirection[] $contractPerformerHasDirections
 * @property Direction[] $directions
 * @property ContractSettings[] $contractSettings
 * @property FavoriteCompany[] $favoriteCompanies
 * @property OfferToCustomer[] $offerToCustomers
 * @property OfferToPerformer[] $offerToPerformers
 * @property Order[] $orders
 * @property OrderComment[] $orderComments
 * @property OrderPerformer[] $orderPerformers
 * @property PaymentHistory[] $paymentHistories
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tariff_id', 'city_id'], 'required'],
            [['tariff_id', 'city_id', 'phone_country_code_1', 'phone_city_code_1', 'phone_num_1', 'phone_country_code_2', 'phone_city_code_2', 'phone_num_2', 'performer_id', 'customer_id'], 'integer'],
            [['name', 'type', 'house', 'corpus', 'building'], 'string', 'max' => 45],
            [['street'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('contract', 'ID'),
            'tariff_id' => Yii::t('contract', 'Tariff ID'),
            'city_id' => Yii::t('contract', 'City ID'),
            'name' => Yii::t('contract', 'Name'),
            'type' => Yii::t('contract', 'Type'),
            'street' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'STREET_PERFORMER_REG_FORM'),
            'house' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'HOUSE_PERFORMER_REG_FORM'),
            'corpus' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'STRUCTURE_PERFORMER_REG_FORM'),
            'building' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'BUILDING_PERFORMER_REG_FORM'),
            'phone_country_code_1' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COUNTRY_PHONE_CODE1_PERFORMER_REG_FORM'),
            'phone_city_code_1' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'CITY_PHONE_CODE1_PERFORMER_REG_FORM'),
            'phone_num_1' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'PHONE_NUMBER1_PERFORMER_REG_FORM'),
            'phone_country_code_2' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COUNTRY_PHONE_CODE2_PERFORMER_REG_FORM'),
            'phone_city_code_2' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'CITY_PHONE_CODE2_PERFORMER_REG_FORM'),
            'phone_num_2' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'PHONE_NUMBER2_PERFORMER_REG_FORM'),
            'phone_1' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'PHONE1_PERFORMER_REG_FORM'),
            'phone_2' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'PHONE2_PERFORMER_REG_FORM'),
            'city' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'CITY_PERFORMER_REG_FORM'),
            'state' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'REGION_PERFORMER_REG_FORM'),
            'country' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COUNTRY_PERFORMER_REG_FORM'),
            'performer_id' => Yii::t('contract', 'Performer ID'),
            'customer_id' => Yii::t('contract', 'Customer ID'),
            'directions' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_ACTIVITIES_PERFORMER_REG_FORM'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new ContractQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Company::className(), ['id' => 'performer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractComments()
    {
        return $this->hasMany(ContractComment::className(), ['contract_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['id' => 'company_id'])->viaTable('contract_performer', ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPerformerHasDirections()
    {
        return $this->hasMany(ContractPerformerHasDirection::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirections()
    {
        return $this->hasMany(Direction::className(), ['id' => 'direction_id'])->viaTable('contract_performer_has_direction', ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractSettings()
    {
        return $this->hasMany(ContractSettings::className(), ['contract_id' => 'id']);
    }

    public function getContractSetting()
    {
        return $this->getContractSettings()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoriteCompanies()
    {
        return $this->hasMany(FavoriteCompany::className(), ['favorite_contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferToCustomers()
    {
        return $this->hasMany(OfferToCustomer::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferToPerformers()
    {
        return $this->hasMany(OfferToPerformer::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderComments()
    {
        return $this->hasMany(OrderComment::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPerformers()
    {
        return $this->hasMany(OrderPerformer::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentHistories()
    {
        return $this->hasMany(PaymentHistory::className(), ['contract_id' => 'id']);
    }

    public static function getCurContract(){
        $contract = null;
        $currentUser = User::getCurrentUser();
        if(ContractModule::module()->interfaceType == 'customer'){
            $contract = $currentUser->customerContracts ? $currentUser->customerContracts[0] : null;
        }
        elseif(ContractModule::module()->interfaceType == 'performer'){
            $contract = $currentUser->performerContracts ? $currentUser->performerContracts[0] : null;
        }
        return $contract;
    }

    public function getUser(){
        if($company =$this->performer){
            $user = $company->users;
            $user = $user ? $user[0] : null;
        }
        elseif($this->customer){
            $user = $this->customer;
        }
        return $user;
    }
}
