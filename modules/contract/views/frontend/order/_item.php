<?
use modules\themes\tender\ThemeAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use modules\contract\Module as ContractModule;

$zeroid=substr($model->id + 10000000000, 1, 11);
$imgPath = ThemeAsset::imgSrc('', 'img');
$buttons = $this->context->getButtons();
?>
<tr data-order="<?=$model->id?>" data-contract="<?=$model->contract_id?>">
    <td class="desc">
        <p>
            <span><b>Заказ ID: <?=$zeroid?></b></span>
            <span><b>Дата размещения: <?=$model->date_publish?></b></span>
        </p>
        <p><b>Краткое описание: <?=$model->short_description?></b></p>
    </td>
    <td>
        <p><b>Бюджет: <?=$model->budget?></b></p>
        <p><b>Количество: <?=$model->count?></b></p>
    </td>
    <td class="links">
        <?foreach ($buttons as $button) :?>
            <p><?=$button::widget([
                    'model' => $model,
                    'pjaxContainerId' => 'pjax-order-modal-container',
                ])?>
            </p>
        <?endforeach;?>
    </td>
</tr>
<tr class="space">
    <td colspan="3"></td>
</tr>