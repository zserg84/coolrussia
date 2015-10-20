<?
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use modules\contract\widgets\comment\CommentWidget;
use modules\contract\models\Order;

?>
<div class="ad"><a href="#">Реклама</a></div>

<table class="orders">
    <tbody>
    <?
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'summary' => false,
    ]);
    ?>
    </tbody>
</table>

<div id="confirm-popup" class="zoom-anim-dialog mfp-hide popup confirm-popup">
    <p class="title">&nbsp;</p>
    <p>Просмотр информации возможен только зарегистрированными пользователями</p>
    <form>
        <a href="<?=Url::toRoute(['/signup/'])?>" class="btn">Ок</a>
        <!-- <button>ОК</button> -->
        <button class="mfp-close-btn">Отмена</button>
    </form>
</div>

<?
Pjax::begin(['id' => 'pjax-order-modal-container', 'enablePushState' => false,]);
Pjax::end();

if(!Yii::$app->getUser()->getIsGuest())
    CommentWidget::widget([
        'contract' => new Order(),
    ]);?>