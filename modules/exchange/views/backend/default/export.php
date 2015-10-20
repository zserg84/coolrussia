<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 11.10.15
 * Time: 18:41
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use modules\lang\models\Lang;
use yii\helpers\ArrayHelper;
use modules\translations\models\MessageCategory;

$form = ActiveForm::begin([
    'options' => [
        'id' => 'export_form',
    ]
]);
?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'lang_id')->dropDownList(ArrayHelper::map(Lang::find()->all(), 'id', 'name'),[
                'prompt' => 'Все'
            ])?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(MessageCategory::find()->all(), 'id', 'name'),[
                'prompt' => 'Все'
            ])?>
        </div>
    </div>
<?= Html::submitButton(
    'Выгрузить',
    [
        'class' => 'btn btn-primary btn-large'
    ]
) ?>
<?ActiveForm::end();