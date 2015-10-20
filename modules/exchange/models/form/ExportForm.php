<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 11.10.15
 * Time: 20:41
 */

namespace modules\exchange\models\form;


use yii\base\Model;

class ExportForm extends Model
{

    public $lang_id;
    public $category_id;

    public function rules(){
        return [
            [['lang_id', 'category_id'], 'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'lang_id' => 'Язык',
            'category_id' => 'Категория',
        ];
    }
} 