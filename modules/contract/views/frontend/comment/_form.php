<?
use yii\widgets\ActiveForm;
use modules\contract\models\form\CommentForm;
use modules\contract\models\ContractComment;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

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
$model = new CommentForm();
$model->contract_id = $contract ? $contract->id : null;

echo $form->field($model, 'contract_id')->hiddenInput()->label(false);
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

    <div class="row">
        <div class="col-sm-4">
            <p>&nbsp;</p>
        </div>
        <div class="col-sm-8 review-type">
            <?
            foreach(ContractComment::estimateList() as $k=>$estimate){
                $class = '';
                switch($k){
                    case -1:
                        $class = 'red';
                        break;
                    case 0:
                        $class = 'gray';
                        break;
                    case 1:
                        $class = 'green';
                        break;
                }
                $checked = $k ? '' : 'checked';
                $active = $k ? '' : 'active';
                $radio = '<label class="costum-radio '.$active.'">
                        <span class="'.$class.'">'.$estimate.'</span>
                        <input type="radio" name="CommentForm[estimate]" '.$checked.' value="'.$k.'">
                     </label>';
                echo $radio;
            }
            ?>
        </div>
    </div>

    <div class="row capcha">
        <div class="cols-sm-3">
            <?=Html::submitButton('Добавить')?>
        </div>
    </div>
</div>
<?ActiveForm::end();