<?php
/**
 * @var array $list
 * @var \modules\event\models\Event $event
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\widgets\ListView;
use modules\event\Module;
use modules\event\models\EventType;
use yii\widgets\Pjax;

$user_id = Yii::$app->getUser()->getId();

if (!Yii::$app->getUser()->isGuest) {
    echo Html::a(Module::t('event', 'Create event'), Url::toRoute('edit'), ['class'=>'btn btn-primary']);
    echo '<br /><br />';
}

Pjax::begin(['id'=>'pjax-form-container']);
$form = ActiveForm::begin([
    'id' => 'event-filter-form',
    'options' => [
        'data-pjax' => '1'
    ],
]);

echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{pager}',
    'itemView' => '_item_view',
]);

?>
<div class="filter">
    <?
    $types = EventType::find()->all();
    foreach($types as $type){
        echo $this->render('_filter_type_item', [
            'type' => $type,
            'form' => $form,
            'model' => $model,
        ]);
    }
    ?>
</div>

<?
ActiveForm::end();
Pjax::end();

$this->registerJsFile(Yii::getAlias('@web/js/jquery-accordion/jquery-ui.js'), ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile(Yii::getAlias('@web/js/jquery-accordion/jquery-ui.css'));
$this->registerJS("
    $('.accordion').accordion({ header: 'h3'});

    $(document).on('click', 'input[type=checkbox]', function(){
        var options = [];
        var serialize = $('#event-filter-form').serialize();console.log(serialize);
        options['data'] = {'form': serialize}
        options['type'] = 'POST';
        $.pjax.reload('#pjax-form-container', options);
    });
", View::POS_READY);