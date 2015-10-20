<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 06.05.15
 * Time: 11:51
 */

namespace modules\contract\models\form;


use yii\base\Model;

class OrderCommentForm extends Model
{
    public $text;

    public $order_id;

    public function rules(){
        return [
            [['text', 'order_id'], 'required'],
            [['text'], 'string'],
        ];
    }

    public function scenarios()
    {
        return [
            'ajax' => [
                'text', 'order_id',
            ],
            'default' => [
                'text', 'order_id',
            ]
        ];
    }
} 