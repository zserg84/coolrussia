<?
use yii\helpers\Url;

echo $this->render('_form', [
    'contract' => $contract,
], $this->context);

$contractId = $contract ? $contract->id : null;
?>
<hr>
<div data-comment="list" class="comments" data-comment-url="<?=Url::to(['list', 'contractId'=>$contractId])?>">
<?=$this->render('_list', [
    'comments' => $comments,
])?>
</div>