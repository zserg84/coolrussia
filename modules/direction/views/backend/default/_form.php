<?php

/**
 * Direction form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\direction\models\Direction $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\direction\Module;
use yii\helpers\Html;
use modules\direction\models\Direction;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$disabledDirection = isset($disabledDirection) ? $disabledDirection : false;

$directions = Direction::find()->where([
    'is', 'parent_id', null
]);
if($model->id)
    $directions->andWhere(['<>', 'id', $model->id]);
$directions = $directions->all();
?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($directions, 'id', 'name'), ['prompt' => 'Select', 'disabled'=>$disabledDirection]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'name') ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $model->isNewRecord ? Module::t('direction', 'BACKEND_CREATE_SUBMIT') : Module::t(
        'direction',
        'BACKEND_UPDATE_SUBMIT'
    ),
    [
        'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>