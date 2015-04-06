<?php
/**
 * @var View $this
 * @var SourceMessage $model
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use modules\lang\models\Lang;
use modules\translations\models\SourceMessage;
use modules\translations\Module;
use modules\translations\models\Message;
use modules\themes\admin\widgets\Box;
use Zelenin\yii\widgets\Alert;


$this->title = Module::t('translations', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('translations', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>

<?

//
//$this->title = Module::t('Update') . ': ' . $model->message;
//echo Breadcrumbs::widget(['links' => [
//    ['label' => Module::t('Translations'), 'url' => ['index']],
//    ['label' => $this->title]
//]]);
//echo Alert::widget();
//?>
<!--<div class="message-update">-->
<!--    <div class="message-form">-->
<!--        <div class="panel panel-default">-->
<!--            <div class="panel-heading">--><?//= Module::t('Source message') ?><!--</div>-->
<!--            <div class="panel-body">--><?//= Html::encode($model->message) ?><!--</div>-->
<!--        </div>-->
<!--        --><?php //$form = ActiveForm::begin(); ?>
<!--        <div class="row">-->
<!--            --><?//
//            $languages = Lang::find()->all();
//            $messages = $model->messages;
//            foreach($languages as $language){
//                $message = isset($messages[$language->id]) ? $messages[$language->id] : new Message();
//                echo $form->field($message, '[' . $language->id . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textInput()->label($language->name);
//            }
//            ?>
<!--        </div>-->
<!--        <div class="form-group">-->
<!--            --><?//=
//            Html::submitButton(
//                $model->getIsNewRecord() ? Module::t('Create') : Module::t('Update'),
//                ['class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']
//            ) ?>
<!--        </div>-->
<!--        --><?php //$form::end(); ?>
<!--    </div>-->
<!--</div>-->
