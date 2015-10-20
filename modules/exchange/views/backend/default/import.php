<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 11.10.15
 * Time: 18:41
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'options' => [
        'id' => 'import_form',
        'enctype' => 'multipart/form-data',
    ]
]);
?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'file')->fileInput()?>
        </div>
    </div>
<?= Html::submitButton(
    'Загрузить',
    [
        'class' => 'btn btn-primary btn-large'
    ]
) ?>
<?ActiveForm::end();