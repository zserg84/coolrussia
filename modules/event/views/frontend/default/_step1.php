<?
/**
 * @var modules\event\models\form\EventForm1 $model
 */

use modules\event\Module;
use modules\event\models\EventType;
use modules\event\models\form\EventForm1;
use frontend\widgets\TimeCalendar\DatePicker;
use frontend\widgets\TimeCalendar\TimeTable;
use frontend\widgets\TimeCalendar\CostTable;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\jui\AutoComplete;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$eventTypes = EventType::find()->all();
$eventTypes = ArrayHelper::map($eventTypes, 'id', 'name');
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'event_types')->checkboxList($eventTypes, [
                'class' => 'event_types',
                'onchange' => "
                    var types = $('.event_types').find('input:checkbox:checked');
                    var eventTypes = {};
                    var eventCategories = {};
                    $(types).each(function() {
                        eventTypes[$(this).val()] = $(this).val();
                    });
                    var cats = $('.event_categories').find('input:checkbox:checked');
                    $(cats).each(function() {
                        eventCategories[$(this).val()] = $(this).val();
                    });
                    $.pjax({
                        type       : 'POST',
                        container  : '#pjax_event_categories',
                        data       : {eventTypes:eventTypes, eventCategories: eventCategories},
                        push       : false,
                        replace    : false,
                    })
                "
            ]) ?>
        </div>
    </div>
    <div class="row">
        <?
//        $eventCategories = $pageParams['eventCategories'];
//        \yii\helpers\VarDumper::dump($eventCategories, 10,1);
        ?>
        <div class="col-sm-12 event_categories">
            <?Pjax::begin(['id' => 'pjax_event_categories', 'enablePushState'=>false])?>
            <?= $form->field($model, 'event_categories')->checkboxList($eventCategories) ?>
            <?Pjax::end()?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'duration') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'duration_type')->radioList($model::getDurationTypes()) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?
            echo $form->field($model, 'city_name')->widget(AutoComplete::className(), [
                'model' => $model,
                'attribute' => 'city_name',
                'clientOptions' => [
                    'minLength' => 2,
                    'source' => new JsExpression("function(request, response) {
                        var __geo_suggest_url = '".Url::toRoute('/geo/suggest/city')."',
                            __geo_country_id = null;//$('#signup__geo_country').val();
                        $.get(__geo_suggest_url, {country_id:__geo_country_id, q:request.term}, function(data) {
                            response(data);
                        }, 'JSON');
                    }"),
                    'select' => new JsExpression("function(event, ui) {
                        $('#geo_city_id').val(ui.item.id);
                    }"),
                ],
                'options' => [
                    'class'=>'form-control',
                ],
            ]);
            echo $form->field($model, 'city_id')->label(false)->hiddenInput(['id'=>'geo_city_id']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'address') ?>
        </div>
    </div>
    <div class="row" id="address_comment">
        <div class="col-sm-12">
            <?= $form->field($model, 'address_comment') ?>
        </div>
    </div>
    <div class="row" id="time_type">
        <div class="col-sm-12">
            <?= $form->field($model, 'time_type')->radioList($model::getTimeTypes(), [
                'class' => 'time_types',
                'onchange' => "
                    var timeType = $('.time_types').find('input:radio:checked').val();
                    $.pjax({
                        type       : 'POST',
                        url        : '#address_comment',
                        container  : '#pjax_event_dates',
                        data       : {timeType:timeType},
                        push       : false,
                        replace    : false,
                    })
                ",
            ]) ?>
        </div>

        <?Pjax::begin(['id' => 'pjax_event_dates', 'enablePushState'=>false])?>
        <div class="datapicker-block">
            <?
            if ($model->time_type == EventForm1::TIME_TYPE_CONCRETE) {
                $model->event_dates = null;
                echo $form->field($model, 'event_dates')->widget(DatePicker::className(), [
                    'name' => 'event_dates',
                    'type' => DatePicker::TYPE_INLINE,
                    'class' => 'datapicker-block',
                    'options' => [
//                        'class'=>'datapicker-block',
                    ],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'multidate' => 5,
                        'multidateSeparator' => ';',
                    ]
                ]);
            }
            elseif ($model->time_type == EventForm1::TIME_TYPE_REPEAT) {
                echo $form->field($model, 'event_dates_repeat')->widget(TimeTable::className(), []);
            }
            elseif ($model->time_type == EventForm1::TIME_TYPE_FREE) {
                echo $form->field($model, 'date_comment')->textarea();
            }
            ?>
        </div>
        <?Pjax::end()?>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'cost_type')->radioList($model::getCostTypes(), [
                'class' => 'cost_types',
                'onchange' => "
                    var costType = $('.cost_types').find('input:radio:checked').val();
                    $.pjax({
                        type       : 'POST',
                        url        : '#pjax_event_dates',
                        container  : '#pjax_event_cost',
                        data       : {costType:costType},
                        push       : false,
                        replace    : false,
                    })
                ",
            ]) ?>
        </div>

        <?Pjax::begin(['id' => 'pjax_event_cost', 'enablePushState'=>false])?>
        <div class="cost-block">
            <?
            if ($model->cost_type == EventForm1::COST_TYPE_FREE) {

            }
            elseif ($model->cost_type == EventForm1::COST_TYPE_ONE) {?>
                <div>
                    <?=$form->field($model, 'event_cost_amount');?> Р за 1 человека
                </div>
                <div>
                    <?= $form->field($model, 'prepayment') ?> %
                </div>
            <?
            }
            elseif ($model->cost_type == EventForm1::COST_TYPE_MANY) {?>
                <div>
                    <?=CostTable::widget([
                        'model' => $model,
                        'attribute' => 'event_costs',
                    ]);?>
                </div>
                <div>
                    <?= $form->field($model, 'prepayment') ?> %
                </div>
            <?}?>

        </div>
        <?Pjax::end()?>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'email') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'phone') ?>
        </div>
    </div>

    <div>
        <?= Html::submitButton(Module::t('event', 'FRONTEND_NEXT_SUBMIT'),
            [
                'class' => 'btn btn-primary btn-large'
            ]
        ) ?>
    </div>
<?php ActiveForm::end(); ?>

<?
$this->registerJS('

', View::POS_READY);