<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 30.04.15
 * Time: 11:46
 */

namespace modules\contract\models\form;

use Yii;
use modules\contract\models\Contract;
use modules\users\models\frontend\User;
use \yii\base\Model;
use yii\behaviors\TimestampBehavior;

class OrderForm extends Model
{

    public $short_description;

    public $description;

    public $date_performance;

    public $date_publish;

    public $material;

    public $count;

    public $budget;

    public $currency_id;

    public $material_belongs_customer;

    public $material_included_budget;

    public $has_modeling;

    public $image_id;

    public $file_model_id;

    public function rules(){
        return [
            [['short_description', 'description', 'date_performance', 'date_publish', 'material', 'count', 'budget',
                'currency_id', 'material_belongs_customer', 'material_included_budget', 'has_modeling'], 'required'],
            [['count', 'budget'], 'number'],
            [['image_id', 'file_model_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'form_name' => \Yii::t('FORM_ORDER', 'ORDER_FORM_NAME'),
            'short_description' => Yii::t('FORM_ORDER', 'ORDER_SHORT_DESCRIPTION'),
            'description' => Yii::t('FORM_ORDER', 'ORDER_DESCRIPTION'),
            'date_performance' => Yii::t('FORM_ORDER', 'ORDER_COMPLETION_DATE'),
            'date_publish' => Yii::t('FORM_ORDER', 'ORDER_PLACEMENT_TIME'),
            'material' => Yii::t('FORM_ORDER', 'ORDER_MATIRIAL'),
            'count' => Yii::t('FORM_ORDER', 'ORDER_NUMBER'),
            'budget' => Yii::t('FORM_ORDER', 'ORDER_BUDGET'),
            'currency_id' => Yii::t('FORM_ORDER', ''),
            'material_belongs_customer' => Yii::t('FORM_ORDER', 'ORDER_MATERIAL_CUSTOMER'),
            'material_included_budget' => Yii::t('FORM_ORDER', 'ORDER_MATERIALS_INCLUDED_IN_THE_BUDGET'),
            'has_modeling' => Yii::t('FORM_ORDER', 'ORDER_MODEL_IS_NECESSARY'),
            'image_id' => Yii::t('FORM_ORDER', 'ORDER_FOTO'),
            'file_model_id' => Yii::t('FORM_ORDER', 'ORDER_MODEL'),
            'file_model_upload' => Yii::t('FORM_ORDER', 'ORDER_MODEL_UPLOAD'),
            'file_model_note' => Yii::t('FORM_ORDER', 'ORDER_MODEL_UPLOAD_NOTE'),
            'note' => Yii::t('FORM_ORDER', 'NOTE'),
            'image_upload' => Yii::t('FORM_ORDER', 'ORDER_UPLOAD_FOTO'),
        ];
    }

    public function scenarios()
    {
        return [
            'default' => [
                'short_description', 'description', 'date_performance', 'date_publish', 'material', 'count', 'budget',
                'currency_id', 'material_belongs_customer', 'material_included_budget', 'has_modeling', 'image_id', 'file_model_id',
            ],
            'ajax' => [
                'short_description', 'description', 'date_performance', 'date_publish', 'material', 'count', 'budget',
                'currency_id', 'material_belongs_customer', 'material_included_budget', 'has_modeling', 'image_id', 'file_model_id',
            ],
        ];
    }


    public function getUser(){
        $user = User::getCurrentUser();
        return $user ? $user->name : null;
    }
}