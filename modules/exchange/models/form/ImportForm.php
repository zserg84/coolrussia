<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 11.10.15
 * Time: 18:41
 */

namespace modules\exchange\models\form;

use yii\base\Model;

class ImportForm extends Model
{

    public $file;

    public function rules(){
        return [
            [['file'], 'required'],
            ['file','file'],
        ];
    }

    public function attributeLabels(){
        return [
            'file' => 'Файл',
        ];
    }
} 