<?
use yii\widgets\ListView;
use yii\web\View;

$this->registerJsFile(Yii::getAlias('@web/js/jquery-accordion/jquery-ui.js'), ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile(Yii::getAlias('@web/js/jquery-accordion/jquery-ui.css'));
$this->registerJS('
    $( "#accordion" ).accordion();
', View::POS_READY);

$data = $dataProvider->getModels();
?>

<div id="accordion">
    <?foreach($data as $model){
        echo $this->render('_faq_item', ['model' => $model]);
    }?>
</div>