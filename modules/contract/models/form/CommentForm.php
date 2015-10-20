<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 28.04.15
 * Time: 15:19
 */

namespace modules\contract\models\form;

class CommentForm extends \yii\base\Model
{

    public $text;

//    public $captcha;

    public $estimate;

    public $contract_id;

    public function rules(){
        return [
            [['text',/* 'captcha',*/ 'contract_id'], 'required'],
            [['text'], 'string'],
            [['estimate'], 'number'],
//            ['captcha', 'captcha', 'captchaAction'=>'/site/default/captcha'],
        ];
    }

    public function scenarios()
    {
        return [
            'ajax' => [
                'text', 'contract_id', 'estimate', /*'captcha',*/
            ],
            'default' => [
                'text', 'contract_id', 'estimate', /*'captcha',*/
            ]
        ];
    }
} 