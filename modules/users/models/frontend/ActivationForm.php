<?php

namespace modules\users\models\frontend;

use modules\users\models\User;
use modules\users\Module;
use modules\users\traits\ModuleTrait;
use yii\base\Model;
use Yii;

/**
 * Class ActivationForm
 * @package modules\users\models
 * ResendForm is the model behind the activation form.
 *
 * @property string $secure_key Activation key
 */
class ActivationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string $token Token
     */
    public $token;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Secure key
            ['token', 'required'],
            ['token', 'trim'],
            ['token', 'string', 'max' => 53],
            [
                'token',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->inactive();
                    }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Module::t('users', 'ATTR_TOKEN')
        ];
    }

    /**
     * Activates user account.
     *
     * @return boolean true if account was successfully activated
     */
    public function activation()
    {
        $model = User::findByToken($this->token, 'inactive');
        if ($model !== null) {
            return $model->activation();
        }
        return false;
    }
}
