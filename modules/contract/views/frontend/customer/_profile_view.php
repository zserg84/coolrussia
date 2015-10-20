<?
use modules\themes\tender\ThemeAsset;
use common\components\Modal;
use yii\helpers\Url;
use modules\contract\Module as ContractModule;
use modules\contract\widgets\comment\CommentWidget;

$imgPath = ThemeAsset::imgSrc('', 'img');

$city = $model->city;
$region = $city->state;
$country= $city->country ? $city->country : $region->country;
$user = $model->customer;
if(!$user) {
    echo 'Ошибка. Заказчика не существует';
    return;
}

Modal::begin([
    'id' => 'profile-modal',
    'header' => '<p class="title">Профиль ' . $user->name . '</p>',
    'footer' => '
        <button class="cancelBtn">Отмена</button>',
    'clientOptions' => false,
    'options' => [
        'data-comment' => 'modal',
    ],
]);

?>
    <div class="popup-wrapper" style="max-height: 430px;">
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

                <div class="row">
                    <div class="col-sm-5">
                        <p><?=$user->getAttributeLabel('name')?></p>
                    </div>
                    <div class="col-sm-7 edit-top-padding">
                        <p class="gray"><?=$user->name?></p>
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
                <p><?=$user->getAttributeLabel('email')?></p>
            </div>
            <div class="col-sm-8">
                <p class="gray"><?=$user->email?></p>
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