<?
use yii\helpers\Url;

echo $this->render('_form', [
    'order' => $order,
], $this->context);

$orderId = $order ? $order->id : null;
?>
<hr>
<div data-comment="list" class="comments" data-comment-url="<?=Url::to(['list', 'orderId'=>$orderId])?>">
<?=$this->render('_list', [
    'comments' => $comments,
])?>
</div>