<?
use modules\themes\tender\ThemeAsset;
use modules\contract\Module as ContractModule;
use yii\helpers\Url;
use yii\helpers\Html;
use modules\contract\models\FavoriteCompany;
use common\components\User;

$imgPath = ThemeAsset::imgSrc('', 'img');

$company = $model->performer;
$user = $model->getUser();
if(!$user)
    return;

$zeroid=substr($user->id + 10000000000, 1, 11);
$buttons = $this->context->getButtons();
?>
<tr data-contract="<?=$model->id?>">
    <td class="ava">
        <img src="<?=$company->getLogo()?>" alt="" widht="78" height="57">
    </td>
    <td class="desc">
        <p>
            <span><b>ID <?=$zeroid?></b></span>
            <span><b>Опыт:</b></span>
            <!-- <span><b>Адрес:</b>г.Москва&nbsp;ул.&nbsp;Сельскохозяйственная,...</span> -->
        </p>
        <p><b>Специализация: <?=$company->specialization?></b></p>
    </td>
    <td class="links">
        <p><img src="<?=$imgPath?>rating.png" alt=""></p>
        <?foreach ($buttons as $button) :?>
            <p><?=$button::widget([
                    'model' => $model,
                    'pjaxContainerId' => 'pjax-item-modal-container',
                ])?>
            </p>
        <?endforeach;?>
    </td>
</tr>
<tr class="space">
    <td colspan="3"></td>
</tr>