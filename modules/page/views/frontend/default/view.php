<?
/**
 * @var $page \modules\page\models\Page
 */
use \yii\helpers\Html;

?>
<h2><?=Html::encode($page->title)?></h2>
<div>
    <?=$page->txt?>
</div>