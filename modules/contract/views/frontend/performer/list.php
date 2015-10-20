<?
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use modules\contract\models\Contract;
use yii\widgets\Pjax;
use modules\contract\widgets\comment\CommentWidget;

$favorite = isset($favorite) ? $favorite : 0;
$currentContract = Contract::getCurContract();
?>

<div class="ad"><a href="#">Реклама</a></div>

<table class="performers">
    <tbody>
    <?
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'summary' => false,
        'viewParams' => [
            'favorite' => $favorite,
            'currentContract' => $currentContract,
        ],
        'layout' => "{items}"
    ]);
    ?>
    </tbody>
</table>
<div>
<?
echo LinkPager::widget([
    'pagination' => $dataProvider->getPagination(),
]);
?>
</div>
<?
Pjax::begin(['id' => 'pjax-item-modal-container', 'enablePushState' => false,]);
Pjax::end();

if(!Yii::$app->getUser()->getIsGuest())
    CommentWidget::widget([
        'contract' => new Contract(),
    ]);?>