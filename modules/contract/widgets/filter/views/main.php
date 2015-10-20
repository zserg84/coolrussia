<?
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use modules\contract\Module as ContractModule;

$form = ActiveForm::begin([
    'options' => [
        'id' => $id,
        'action' => $action,
    ]
]);
foreach($filterParams as $k=>$filter):
    $firstClass = $k ? '' : 'first';
    ?>
<p class="title <?=$firstClass?>"><?=$filter['name']?></p>
<?=$this->render($filter['filterView']); ?>
<div class="accept-clear">
    <a href="javascript:void(0)" class="resetLink"><?=ContractModule::t('ALL_INTERFACES', 'DIRECTIONS_FILTER_CLEAR_BUTTON')?></a>
    <a href="javascript:void(0)" class="submitLink"><?=ContractModule::t('ALL_INTERFACES', 'DIRECTIONS_FILTER_APPLY_BUTTON')?></a>
</div>

<?endforeach;?>
<?ActiveForm::end();
$this->registerJs('
    $(document).on("click", ".submitLink", function(){
        $(this).closest("form").submit();
    });

    $(document).on("click", ".resetLink", function(){
        var form = $(this).closest("form");
        form.find("input[name!=_csrf]").val("");
    });
');
