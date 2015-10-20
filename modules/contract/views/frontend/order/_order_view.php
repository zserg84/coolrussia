<div id="response-modal-block"></div>
<?
use modules\themes\tender\ThemeAsset;
use yii\helpers\ArrayHelper;
use common\components\Modal;
use yii\helpers\Url;

$imgPath = ThemeAsset::imgSrc('', 'img');

$contract = $model->contract;
$contractUser = $contract->getUser();

Modal::begin([
    'id' => 'order-modal',
    'header' => '<p class="title">Заказ ID '.$model->id.'</p>',
    'footer' => '
        <button onclick="
            $.get(\''.Url::toRoute(['/contract/order/response']).'\', {orderId: '.$model->id.'}, function(data){
                $(\'#response-modal-block\').html(data);
                $(\'.modal\').hide();
                initPage();
                $(\'#response-modal\').modal();
            })
        ">Отклик на заказ</button>
        <button class="cancelBtn">Отмена</button>',
    'clientOptions' => false,
    'options' => [
        'data-comment' => 'modal',
    ],
]);

?>
    <div class="popup-wrapper" style="max-height: 430px;">
        <div class="row order-review-top">
            <div class="col-sm-6">

                <div class="row">
                    <div class="col-sm-6">
                        <p>Разместил пользователь</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=$contractUser->name?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Дата размещения заказа</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asDate($model->created_at)?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Срок исполнения до</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asDate($model->date_performance)?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Срок размещения</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asDate($model->date_publish)?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Бюджет</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=$model->budget . ' '. $model->currency->name?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Количество</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=$model->count?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Материал</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=$model->material?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Материал включен в бюджет</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asBoolean($model->material_included_budget)?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Материал заказчика</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asBoolean($model->material_belongs_customer)?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p>Требуется моделирование</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="gray"><?=Yii::$app->getFormatter()->asBoolean($model->has_modeling)?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                        <p>Фото</p>
                    </div>
                    <div class="col-sm-10">
                        <div class="row photos">
                            <div class="col-sm-6 item">
                                <img src="<?=$model->getLogo()?>" width="120" height="120">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <p>Краткое описание</p>
            </div>
            <div class="col-sm-8">
                <p class="gray"><?=$model->short_description?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <p>Описание</p>
            </div>
            <div class="col-sm-9">
                <p class="gray"><?=$model->description?></p>
            </div>
        </div>
        <?
        $directions = $model->directions;
        $directions = $directions ? ArrayHelper::map($directions, 'id', 'name') : [];
        $directions = implode(', ', $directions);
        ?>
        <div class="row">
            <div class="col-sm-3">
                <p><?=$model->getAttributeLabel('directions')?></p>
            </div>
            <div class="col-sm-9">
                <p class="gray"><?=$directions?></p>
            </div>
        </div>
        <?
        echo \modules\contract\widgets\comment\CommentOrderWidget::widget([
            'order' => $model,
        ]);
        ?>
    </div>

<?
Modal::end();