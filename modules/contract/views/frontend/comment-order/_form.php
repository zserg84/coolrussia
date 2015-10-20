<?
use yii\widgets\ActiveForm;
use modules\contract\models\form\OrderCommentForm;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

$model = new OrderCommentForm();
$model->order_id = $order ? $order->id : null;

$form = ActiveForm::begin(
    [
        'enableAjaxValidation' => false,
        'action' => Url::toRoute(['create']),
        'options' => [
            'id' => 'comment_form',
            'data-comment-action' => 'create',
        ]
    ]
);

echo $form->field($model, 'order_id')->hiddenInput()->label(false);
?>
<div class="form-group" data-comment="form-group">
    <div class="row">
        <div class="col-sm-4">
            <p>Добавить коментарий</p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'text')->textarea(['rows' => 5])->label(false)?>
        </div>
    </div>

    <div class="row capcha">
        <div class="cols-sm-3">
            <?=Html::submitButton('Добавить')?>
        </div>
    </div>
</div>
<?ActiveForm::end();