<?php

namespace common\models;

use modules\contract\models\Contract;
use modules\image\models\Image;
use modules\users\models\User;
use Yii;
use modules\contract\Module as ContractModule;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $about
 * @property string $specialization
 * @property integer $count_years
 * @property string $additional_info
 * @property string $site
 * @property string $email_for_order
 * @property integer $image_id
 *
 * @property Image $image
 * @property CompanyImage[] $companyImages
 * @property Contract[] $contracts
 * @property UserHasCompany[] $userHasCompanies
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{

    const DEFAULT_COVER_SRC = '';

    public $logo;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_for_order', 'specialization'], 'required'],
            [['id', 'count_years', 'image_id'], 'integer'],
            [['about', 'additional_info'], 'string'],
            [['name', 'site', 'email_for_order'], 'string', 'max' => 45],
            [['specialization'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('m_company', 'ID'),
            'name' => Yii::t('m_company', 'Name'),
            'about' => Yii::t('m_company', 'About'),
            'specialization' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_SPECIALIZATION_PERFORMER_REG_FORM'),
            'count_years' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'YEARS_IN_THE_MARKET_PERFORMER_REG_FORM'),
            'additional_info' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'ADDITIONAL_INFO'),
            'site' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'COMPANY_SITE_PERFORMER_REG_FORM'),
            'email_for_order' => ContractModule::t('REGISTRATION_FORM_PERFORMER', 'EMAIL_FOR_OBTAINING_ORDERS_REG_FORM'),
            'image_id' => Yii::t('m_company', 'Logo Image ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyImages()
    {
        return $this->hasMany(CompanyImage::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['performer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasCompanies()
    {
        return $this->hasMany(UserHasCompany::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_company', ['company_id' => 'id']);
    }

    public function getUser()
    {
        $user = $this->users;
        return $user ? $user[0] : null;
    }

    public function saveLogo() {
        if (is_null($this->logo)) return;
        if (($tmpName = $this->logo->tempName) and ($ext = $this->logo->extension)) {
            if ($image = Image::GetByFile($tmpName, $ext)) {
                $this->image_id = $image->id;
                $this->save();
            }
        }
    }

    public function getLogo($w=null) {
        if ($this->image_id) {
            return $this->image->getSrc($w);
        }
        return self::DEFAULT_COVER_SRC;
    }
}
