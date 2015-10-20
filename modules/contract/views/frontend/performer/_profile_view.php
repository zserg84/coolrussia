<div id="offer-modal-block"></div>
<?
use modules\themes\tender\ThemeAsset;
use yii\helpers\ArrayHelper;
use common\components\Modal;
use yii\helpers\Url;
use modules\contract\Module as ContractModule;
use modules\contract\widgets\comment\CommentWidget;

$imgPath = ThemeAsset::imgSrc('', 'img');

$city = $model->city;
$region = $city->state;
$country= $city->country ? $city->country : $region->country;
$company = $model->performer;
if(!$company) {
    echo 'Ошибка. Компании-исполнителя не существует';
    return;
}
$user = $company->users[0];
$orderId = $order ? $order->id : "1";

Modal::begin([
    'id' => 'profile-modal',
    'header' => '<p class="title">Профиль ' . $company->name . '</p>',
    'footer' => '
        <button onclick="
            $.get(\''.Url::toRoute(['/contract/performer/offer-order']).'\', {contractId: '.$model->id.'}, function(data){
                $(\'#offer-modal-block\').html(data);
                $(\'.modal\').hide();
                $(\'#offer-order-modal\').modal();
            })
        ">Предложить заказ</button>
        <a href="'.Url::toRoute(['favorite-add', 'favoriteContractId'=>$model->id]).'">'.ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_FAVOURITES_BUTTON').'</a>
        <button class="cancelBtn">Отмена</button>',
    'clientOptions' => false,
    'options' => [
        'data-comment' => 'modal',
    ],
]);

?>
<div class="popup-wrapper">
    <div class="row margin0">
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-5">
                    <p>ID</p>
                </div>
                <div class="col-sm-7 edit-top-padding">
                    <p class="gray"><?=$model->id?></p>
                </div>
            </div>

            <?
            $directions = $model->directions;
            $directions = $directions ? ArrayHelper::map($directions, 'id', 'name') : [];
            $directions = implode(', ', $directions);
            ?>
            <div class="row">
                <div class="col-sm-5">
                    <p><?=$model->getAttributeLabel('directions')?></p>
                </div>
                <div class="col-sm-7 edit-top-padding">
                    <p class="gray"><?=$directions?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <p><?=$company->getAttributeLabel('specialization')?></p>
                </div>
                <div class="col-sm-7 edit-top-padding">
                    <p class="gray"><?=$company->specialization?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 acc-info">
            <p><img src="<?=$user->getLogo()?>" alt="" width="100px" height="100px"></p>
            <p class="rating"><a href="#"><span>Рейтинг</span><img src="<?=$imgPath?>rating.png" alt=""></a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$user->getAttributeLabel('created_at')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=Yii::$app->getFormatter()->asDate($user->created_at)?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$company->getAttributeLabel('count_years')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$company->count_years?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$company->getAttributeLabel('additional_info')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$company->additional_info?></p>
        </div>
    </div>

    <div class="row margin0">
        <div class="col-sm-4">
            <p>Примеры работ</p>
        </div>
        <div class="col-sm-8">
            <div class="row ">
                <div class="col-sm-12 perftomrer-works-block">
                    <?foreach($company->companyImages as $image):?>
                        <div class="col-sm-3 item">
                            <img src="<?=$image->getLogo()?>" width="100" height="100">
                        </div>
                    <?endforeach?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$company->getAttributeLabel('site')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$company->site?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$user->getAttributeLabel('email')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$user->email?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$company->getAttributeLabel('email_for_order')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$company->email_for_order?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('phone_1')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$model->phone_num_1?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('phone_2')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$model->phone_num_2?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('country')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$country->name?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('state')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$region->name?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('city')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$city->name?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('street')?></p>
        </div>
        <div class="col-sm-8">
            <p class="gray"><?=$model->street?></p>
        </div>
    </div>

    <hr>
    <?echo CommentWidget::widget([
        'contract' => $model,
    ]);?>
</div>
<?
Modal::end();