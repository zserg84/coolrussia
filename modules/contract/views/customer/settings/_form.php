<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:53
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$form = ActiveForm::begin(
    [
        'action' => Url::toRoute(['/contract/settings/update']),
        'enableAjaxValidation' => true,
        'options' => [
            'id' => 'settings_form',
        ],
        'fieldClass'=>\common\components\ActiveField::className()
    ]
);?>
    <div class="popup-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li>
<!--                        Отправлять уведомления на E-mail об откликах Исполнителей на заказ-->
                        <?=$form->field($model, 'notification_of_permormer_response')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel('notification_of_permormer_response').' </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                    <li>
                        <!-- Применить при входе в Систему территориальный фильтр в соответствии с моим территориальным месторасположением-->
                        <?=$form->field($model, 'apply_filter_territory')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel("apply_filter_territory").'  </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?ActiveForm::end();
