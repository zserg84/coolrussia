<?
use modules\contract\Module as ContractModule;

$spec = Yii::$app->getRequest()->post('filter_specialization');
?>
<div class="spec-filtr">
    <input type="text" name="filter_specialization" placeholder="<?=ContractModule::t('ALL_INTERFACES', 'ENTER_SPECIALIZATION')?>" value="<?=$spec?>"/>
</div>