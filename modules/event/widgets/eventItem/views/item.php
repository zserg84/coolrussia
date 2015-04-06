<?
use modules\event\Module;
use yii\helpers\Html;
?>
<div>
    <div class="header">
        <span class="like"></span>
        <span class="languages">
            <?
            foreach($languages as $language){
                echo Html::tag('span', $language->name);
            }
            ?>
        </span>
        <span class="info">
            <?=$timeToEvent['value']. ' '. $timeToEvent['dimension']?>
        </span>
    </div>
    <div class="picture">
        <?
        $img = Html::img($model->getCover());
        if($editUrl)
            echo Html::a(Html::img($model->getCover()), $editUrl);
        else echo $img;
        ?>
    </div>
    <div class="content">
        <div><h3><?=$model->getTitle()?></h3></div>
        <div><?=$datesList?></div>
        <div>
            <?
            if($costArr){
                $cost = '';
                if(count($costArr) > 1)
                    $cost = 'от ';
                $cost .= min($costArr) . ' Р';
            }
            else
                $cost = Module::t('event', 'COST_TYPE_FREE');

            echo $cost;
            ?>
            <?=$model->prepayment ? $model->prepayment . '% предоплаты' : ''?>
        </div>
    </div>
</div>